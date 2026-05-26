const fs = require('fs');
const path = require('path');

const VALID_NODE_PREFIXES = new Set([
    "file", "function", "class", "module", "concept",
    "config", "document", "service", "table", "endpoint",
    "pipeline", "schema", "resource",
    "domain", "flow", "step",
    "article", "entity", "topic", "claim", "source"
]);

const TYPE_TO_PREFIX = {
    "file": "file",
    "function": "function",
    "func": "function",
    "class": "class",
    "module": "module",
    "concept": "concept",
    "config": "config",
    "document": "document",
    "service": "service",
    "table": "table",
    "endpoint": "endpoint",
    "pipeline": "pipeline",
    "schema": "schema",
    "resource": "resource",
    "domain": "domain",
    "flow": "flow",
    "step": "step",
    "article": "article",
    "entity": "entity",
    "topic": "topic",
    "claim": "claim",
    "source": "source"
};

function normalizeNodeId(nodeId, node) {
    let nid = nodeId;

    // Double prefix
    for (const prefix of VALID_NODE_PREFIXES) {
        const double = `${prefix}:${prefix}:`;
        if (nid.startsWith(double)) {
            nid = nid.slice(prefix.length + 1);
            break;
        }
    }

    // Canonicalize legacy
    if (nid.startsWith("func:") && !nid.startsWith("function:")) {
        nid = "function:" + nid.slice(5);
    }

    // Add missing prefix
    const hasPrefix = Array.from(VALID_NODE_PREFIXES).some(p => nid.startsWith(p + ":"));
    if (!hasPrefix) {
        const nodeType = node.type || "file";
        const prefix = TYPE_TO_PREFIX[nodeType] || "file";
        if (nodeType === "function" || nodeType === "class") {
            const filePath = node.filePath || "";
            const name = node.name || nid;
            if (filePath) {
                nid = `${prefix}:${filePath}:${name}`;
            } else {
                nid = `${prefix}:__nofilepath__:${name}`;
            }
        } else {
            nid = `${prefix}:${nid}`;
        }
    }
    return nid;
}

function normalizeComplexity(value) {
    if (typeof value === 'string') {
        const lower = value.trim().toLowerCase();
        if (['simple', 'moderate', 'complex'].includes(lower)) return lower;
        if (['low', 'easy'].includes(lower)) return 'simple';
        if (['medium', 'intermediate'].includes(lower)) return 'moderate';
        if (['high', 'hard', 'difficult'].includes(lower)) return 'complex';
        return 'moderate';
    } else if (typeof value === 'number') {
        if (value <= 3) return 'simple';
        if (value <= 6) return 'moderate';
        return 'complex';
    }
    return 'moderate';
}

async function main() {
    const projectRoot = process.argv[2];
    const intermediateDir = path.join(projectRoot, '.understand-anything', 'intermediate');
    const files = fs.readdirSync(intermediateDir).filter(f => f.startsWith('batch-') && f.endsWith('.json') && !f.includes('input') && !f.includes('structure'));

    let allNodes = [];
    let allEdges = [];
    let edgesRewritten = 0;

    files.forEach(f => {
        const data = JSON.parse(fs.readFileSync(path.join(intermediateDir, f), 'utf8'));
        if (data.nodes) allNodes.push(...data.nodes);
        if (data.edges) allEdges.push(...data.edges);
    });

    const idMapping = {};
    allNodes.forEach(node => {
        const originalId = node.id;
        const correctedId = normalizeNodeId(originalId, node);
        if (correctedId !== originalId) {
            idMapping[originalId] = correctedId;
            node.id = correctedId;
        }
        node.complexity = normalizeComplexity(node.complexity);
    });

    allEdges.forEach(edge => {
        const src = edge.source || edge.from || "";
        const tgt = edge.target || edge.to || "";
        const newSrc = idMapping[src] || src;
        const newTgt = idMapping[tgt] || tgt;
        if (newSrc !== src || newTgt !== tgt) {
            edgesRewritten += 1;
        }
        edge.source = newSrc;
        edge.target = newTgt;
        delete edge.from;
        delete edge.to;
    });

    const nodesById = {};
    allNodes.forEach(node => {
        nodesById[node.id] = node;
    });

    const nodeIds = new Set(Object.keys(nodesById));
    const edgesByKey = {};
    allEdges.forEach(edge => {
        if (nodeIds.has(edge.source) && nodeIds.has(edge.target)) {
            const key = `${edge.source}->${edge.target}:${edge.type}`;
            const weight = parseFloat(edge.weight) || 0;
            if (!edgesByKey[key] || weight > (parseFloat(edgesByKey[key].weight) || 0)) {
                edgesByKey[key] = edge;
            }
        }
    });

    // Recover imports from scan-result.json
    const scanPath = path.join(intermediateDir, 'scan-result.json');
    if (fs.existsSync(scanPath)) {
        const scan = JSON.parse(fs.readFileSync(scanPath, 'utf8'));
        if (scan.importMap) {
            Object.entries(scan.importMap).forEach(([srcPath, targets]) => {
                const srcId = `file:${srcPath}`;
                if (!nodeIds.has(srcId)) return;
                targets.forEach(tgtPath => {
                    const tgtId = `file:${tgtPath}`;
                    if (!nodeIds.has(tgtId) || srcId === tgtId) return;
                    const key = `${srcId}->${tgtId}:imports`;
                    if (!edgesByKey[key]) {
                        edgesByKey[key] = {
                            source: srcId,
                            target: tgtId,
                            type: "imports",
                            direction: "forward",
                            weight: 0.7,
                            recoveredFromImportMap: true
                        };
                    }
                });
            });
        }
    }

    const assembled = {
        nodes: Object.values(nodesById),
        edges: Object.values(edgesByKey)
    };

    fs.writeFileSync(path.join(intermediateDir, 'assembled-graph.json'), JSON.stringify(assembled, null, 2));
    console.log(`Merged ${allNodes.length} nodes and ${allEdges.length} edges into ${assembled.nodes.length} nodes and ${assembled.edges.length} edges.`);
}

main().catch(console.error);
