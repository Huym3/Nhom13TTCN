<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($deThi->TenDeThi); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-base:    #f0f4f8;
            --blue:       #2563eb;
            --blue-bright:#3b82f6;
            --blue-dim:   rgba(37,99,235,0.10);
            --blue-border:rgba(37,99,235,0.28);
            --glass-bg:         rgba(255,255,255,0.65);
            --glass-border:     rgba(255,255,255,0.85);
            --glass-border-strong: rgba(203,213,225,0.9);
            --glass-shadow:     0 4px 24px rgba(15,23,42,0.08);
            --text-primary:   #0f172a;
            --text-secondary: #334155;
            --text-muted:     #64748b;
            --green: #059669; --red: #dc2626; --amber: #d97706;
            --radius-sm: 8px; --radius-md: 12px; --radius-lg: 18px;
            --font-main: 'Plus Jakarta Sans', -apple-system, sans-serif;
            --font-mono: 'DM Mono', monospace;
        }

        html, body {
            font-family: var(--font-main);
            background-color: var(--bg-base);
            color: var(--text-primary);
            font-size: 14px; line-height: 1.6;
        }
        body {
            background:
                radial-gradient(ellipse 700px 500px at -5% -10%, rgba(37,99,235,0.10) 0%, transparent 60%),
                radial-gradient(ellipse 500px 400px at 105% 100%, rgba(59,130,246,0.08) 0%, transparent 60%),
                var(--bg-base);
            background-attachment: fixed;
        }

        /* ── Header ── */
        .exam-header {
            position: sticky; top: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            padding: 13px 28px;
            background: rgba(255,255,255,0.75);
            border-bottom: 1px solid var(--glass-border-strong);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            box-shadow: 0 1px 16px rgba(15,23,42,0.07);
        }
        .exam-title {
            font-size: 14px; font-weight: 600; color: var(--text-primary);
            flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .timer {
            display: flex; align-items: center; gap: 8px;
            padding: 7px 16px; border-radius: 40px;
            background: rgba(255,255,255,0.8);
            border: 1px solid var(--glass-border-strong);
            box-shadow: 0 2px 8px rgba(15,23,42,0.07);
        }
        #countdown {
            font-size: 18px; font-weight: 700;
            color: var(--blue); font-family: var(--font-mono);
            letter-spacing: -0.5px; font-variant-numeric: tabular-nums;
        }
        .timer.warning #countdown { color: var(--amber); }
        .timer.danger  #countdown { color: var(--red); animation: blink 1s step-end infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.4} }

        .btn-submit {
            background: var(--blue); color: #fff;
            border: 1px solid var(--blue); border-radius: var(--radius-sm);
            padding: 8px 20px; font-size: 13px; font-weight: 600;
            font-family: var(--font-main); cursor: pointer;
            transition: background 0.15s, box-shadow 0.15s;
        }
        .btn-submit:hover { background: #1d4ed8; box-shadow: 0 4px 14px rgba(37,99,235,0.35); }

        /* ── Progress bar ── */
        .progress-strip { height: 2px; background: #e2e8f0; position: sticky; top: 57px; z-index: 99; }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--blue), var(--blue-bright));
            transition: width 0.4s ease; width: 0%;
        }

        /* ── Body ── */
        .exam-body { padding: 20px 28px 60px; }

        /* ── Layout: 3/4 main + 1/4 sidebar ── */
        .exam-layout {
            display: grid;
            grid-template-columns: 3fr 1fr;
            gap: 20px;
            align-items: flex-start;
        }
        .exam-main { min-width: 0; }

        /* ── Section title ── */
        .phan-title {
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.12em; color: var(--text-muted);
            display: flex; align-items: center; gap: 10px;
            margin: 28px 0 14px;
        }
        .phan-title::after { content: ''; flex: 1; height: 1px; background: var(--glass-border-strong); }
        .phan-title:first-child { margin-top: 0; }

        /* ── Question card ── */
        .question-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-lg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: var(--glass-shadow);
            padding: 20px 22px; margin-bottom: 12px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .question-card.answered {
            border-color: rgba(37,99,235,0.3);
            box-shadow: 0 4px 24px rgba(37,99,235,0.08);
        }

        .question-number {
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: var(--text-muted); margin-bottom: 10px;
        }
        .question-content {
            font-size: 14px; color: var(--text-secondary);
            line-height: 1.7; margin-bottom: 16px;
        }
        .question-content img { max-width: 100%; border-radius: 10px; }
        .question-image img   { max-width: 100%; border-radius: 10px; margin-bottom: 14px; }

        /* ── Options (Phần I) ── */
        .options { display: flex; flex-direction: column; gap: 8px; }
        .option {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 14px; border-radius: var(--radius-md);
            background: rgba(255,255,255,0.5);
            border: 1px solid rgba(203,213,225,0.7);
            cursor: pointer; transition: all 0.15s; user-select: none;
        }
        .option:hover { background: rgba(255,255,255,0.9); border-color: rgba(37,99,235,0.25); }
        .option.selected {
            background: var(--blue-dim);
            border-color: var(--blue-border);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
        }
        .option input[type="radio"] { display: none; }
        .option-key {
            width: 26px; height: 26px; flex-shrink: 0; border-radius: 6px;
            background: #f1f5f9; border: 1px solid #cbd5e1;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: var(--text-muted);
            transition: all 0.15s;
        }
        .option.selected .option-key {
            background: var(--blue-dim); border-color: var(--blue-border); color: var(--blue);
        }
        .option-text { font-size: 13px; color: var(--text-secondary); transition: color 0.15s; }
        .option.selected .option-text { color: var(--text-primary); font-weight: 500; }

        /* ── DS table (Phần II) ── */
        .ds-table { width: 100%; max-width: 440px; margin: 0 auto; border-collapse: collapse; }
        .ds-table th {
            font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em;
            color: var(--text-muted); padding: 8px 14px; text-align: center;
            border-bottom: 1px solid var(--glass-border-strong);
            background: rgba(241,245,249,0.7);
        }
        .ds-table td { padding: 10px 14px; text-align: center; border-bottom: 1px solid rgba(203,213,225,0.4); }
        .ds-table tr:last-child td { border-bottom: none; }
        .ds-table td strong { font-size: 14px; font-weight: 700; color: var(--text-primary); }
        .ds-table input[type="radio"] { accent-color: var(--blue); width: 16px; height: 16px; cursor: pointer; }

        /* ── TLS input (Phần III) ── */
        .tls-input { display: flex; align-items: center; gap: 12px; margin-top: 14px; }
        .tls-input label {
            font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;
            color: var(--text-muted); white-space: nowrap;
        }
        .tls-input input[type="number"] {
            background: rgba(255,255,255,0.8); border: 1px solid var(--glass-border-strong);
            border-radius: var(--radius-sm); color: var(--text-primary);
            padding: 9px 14px; font-size: 15px; font-family: var(--font-mono);
            width: 200px; outline: none; transition: border-color 0.15s, box-shadow 0.15s;
        }
        .tls-input input[type="number"]:focus {
            border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
        }
        .tls-input input[type="number"]::placeholder { color: #94a3b8; }

        /* ── Submit area ── */
        .submit-area { text-align: center; margin-top: 36px; }
        .btn-submit-big {
            background: var(--blue); color: #fff;
            border: 1px solid var(--blue); border-radius: var(--radius-md);
            padding: 14px 44px; font-size: 15px; font-weight: 700;
            font-family: var(--font-main); cursor: pointer;
            transition: background 0.15s, transform 0.1s, box-shadow 0.15s;
        }
        .btn-submit-big:hover {
            background: #1d4ed8; transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37,99,235,0.35);
        }

        /* ── Question map sidebar ── */
        .q-map {
            position: sticky; top: 80px;
            background: var(--glass-bg); border: 1px solid var(--glass-border);
            border-radius: var(--radius-lg); backdrop-filter: blur(16px);
            box-shadow: var(--glass-shadow); padding: 16px;
        }
        .q-map-title {
            font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;
            color: var(--text-muted); margin-bottom: 12px;
        }
        .q-map-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 5px; }
        .q-dot {
            aspect-ratio: 1;
            border-radius: 6px;
            background: #f1f5f9; border: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 600; color: var(--text-muted);
            cursor: pointer; transition: all 0.15s;
        }
        .q-dot:hover { background: #e0e7ff; border-color: var(--blue-border); color: var(--blue); }
        .q-dot.done  { background: var(--blue-dim); border-color: var(--blue-border); color: var(--blue); }
        .q-map-footer {
            margin-top: 12px; font-size: 11px; color: var(--text-muted);
            padding-top: 10px; border-top: 1px solid var(--glass-border-strong);
        }
        .q-map-footer span { font-weight: 700; color: var(--blue); }
    </style>
</head>
<body>

<div class="exam-header">
    <div class="exam-title"><?php echo e($deThi->TenDeThi); ?></div>
    <div class="timer" id="timer">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--blue); flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span id="countdown"><?php echo e($deThi->ThoiGian); ?>:00</span>
    </div>
    <button class="btn-submit" onclick="xacNhanNopBai()">Nộp bài</button>
</div>

<div class="progress-strip">
    <div class="progress-fill" id="progressFill"></div>
</div>

<div class="exam-body">
<div class="exam-layout">
<div class="exam-main">

    
    <?php if($phan1->count() > 0): ?>
    <div class="phan-title">Phần I — Trắc nghiệm (<?php echo e($phan1->count()); ?> câu × 0.25đ)</div>
    <?php $__currentLoopData = $phan1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cau): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="question-card" id="q<?php echo e($cau->MaCauHoi); ?>">
        <div class="question-number">Câu <?php echo e($i + 1); ?></div>
        <div class="question-content">
            <?php if($cau->HinhAnh): ?>
                <img src="<?php echo e(asset('storage/' . $cau->HinhAnh)); ?>" alt="Câu hỏi <?php echo e($i + 1); ?>">
            <?php else: ?>
                <?php echo $cau->NoiDungCH; ?>

            <?php endif; ?>
        </div>
        <div class="options">
            <?php $__currentLoopData = $cau->dapAn; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $da): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="option" id="opt_<?php echo e($cau->MaCauHoi); ?>_<?php echo e($da->MaDATN); ?>">
                <input type="radio"
                    name="phan1_<?php echo e($cau->MaCauHoi); ?>"
                    value="<?php echo e($da->MaDATN); ?>"
                    onchange="luuTN(<?php echo e($cau->MaCauHoi); ?>, <?php echo e($da->MaDATN); ?>, this)">
                <span class="option-key"><?php echo e($da->KyHieu); ?></span>
                <span class="option-text"><?php echo e($da->NoiDungDapAn); ?></span>
            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    
    <?php if($phan2->count() > 0): ?>
    <div class="phan-title">Phần II — Đúng/Sai (<?php echo e($phan2->count()); ?> câu)</div>
    <?php $__currentLoopData = $phan2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cau): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="question-card" id="q<?php echo e($cau->MaCauHoi); ?>">
        <div class="question-number">Câu <?php echo e($i + 1); ?></div>
        <?php if($cau->HinhAnh): ?>
        <div class="question-image">
            <img src="<?php echo e(asset('storage/' . $cau->HinhAnh)); ?>" alt="Câu hỏi <?php echo e($i + 1); ?>">
        </div>
        <?php endif; ?>
        <table class="ds-table">
            <thead>
                <tr><th>Ý</th><th>Đúng</th><th>Sai</th></tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $cau->cacY; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><strong><?php echo e(strtoupper($y->KyHieu)); ?></strong></td>
                    <td>
                        <input type="radio"
                               name="ds_<?php echo e($cau->MaCauHoi); ?>_<?php echo e($y->MaY); ?>"
                               value="1"
                               onchange="luuDS(<?php echo e($cau->MaCauHoi); ?>, <?php echo e($y->MaY); ?>, 1)">
                    </td>
                    <td>
                        <input type="radio"
                               name="ds_<?php echo e($cau->MaCauHoi); ?>_<?php echo e($y->MaY); ?>"
                               value="0"
                               onchange="luuDS(<?php echo e($cau->MaCauHoi); ?>, <?php echo e($y->MaY); ?>, 0)">
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    
    <?php if($phan3->count() > 0): ?>
    <div class="phan-title">Phần III — Trả lời ngắn (<?php echo e($phan3->count()); ?> câu × 0.5đ)</div>
    <?php $__currentLoopData = $phan3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cau): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="question-card" id="q<?php echo e($cau->MaCauHoi); ?>">
        <div class="question-number">Câu <?php echo e($i + 1); ?></div>
        <div class="question-content">
            <?php if($cau->HinhAnh): ?>
                <img src="<?php echo e(asset('storage/' . $cau->HinhAnh)); ?>" alt="Câu hỏi <?php echo e($i + 1); ?>">
            <?php else: ?>
                <?php echo $cau->NoiDungCH; ?>

            <?php endif; ?>
        </div>
        <div class="tls-input">
            <label>Đáp án:</label>
            <input type="number"
                step="0.01"
                placeholder="Nhập số..."
                onchange="luuSo(<?php echo e($cau->MaCauHoi); ?>, this.value)"
                id="tls_<?php echo e($cau->MaCauHoi); ?>">
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    <div class="submit-area">
        <button class="btn-submit-big" onclick="xacNhanNopBai()">Nộp bài</button>
    </div>

</div>


<div class="q-map" id="qMap">
    <div class="q-map-title">Câu hỏi</div>
    <div class="q-map-grid" id="qMapGrid">
        <?php $total = $phan1->count() + $phan2->count() + $phan3->count(); ?>
        <?php for($n = 1; $n <= $total; $n++): ?>
        <div class="q-dot" data-n="<?php echo e($n); ?>" onclick="scrollToQ(<?php echo e($n); ?>)"><?php echo e($n); ?></div>
        <?php endfor; ?>
    </div>
    <div style="margin-top: 12px; font-size: 11px; color: var(--text-muted);
                padding-top: 10px; border-top: 1px solid rgba(203,213,225,0.7);">
        Đã làm: <span style="font-weight:700; color:var(--blue);" id="doneCount">0</span>/<span><?php echo e($total); ?></span>
    </div>
</div>

</div>
</div>

<form id="formNopBai" method="POST" action="<?php echo e(route('student.exams.submit')); ?>" style="display:none;">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="maBaiLam" value="<?php echo e($maBaiLam); ?>">
</form>

<script>
    const MA_BAI_LAM = <?php echo json_encode($maBaiLam, 15, 512) ?>;
    const THOI_GIAN  = <?php echo json_encode($giayConLai, 15, 512) ?>;
    const URL_TN     = <?php echo json_encode(route('student.exams.saveTN'), 15, 512) ?>;
    const URL_DS     = <?php echo json_encode(route('student.exams.saveDS'), 15, 512) ?>;
    const URL_SO     = <?php echo json_encode(route('student.exams.saveSo'), 15, 512) ?>;
    const CSRF_TOKEN = document.querySelector('meta[name=csrf-token]').content;
    const DA_CHON_TN = <?php echo json_encode($daDuocChonTN, 15, 512) ?>;
    const DA_CHON_DS = <?php echo json_encode($daDuocChonDS, 15, 512) ?>;
    const DA_CHON_SO = <?php echo json_encode($daDuocChonSo, 15, 512) ?>;
    const TOTAL_Q    = <?php echo e($total ?? ($phan1->count() + $phan2->count() + $phan3->count())); ?>;
</script>
<script src="<?php echo e(asset('js/exam.js')); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Restore Phần I — TN
    Object.entries(DA_CHON_TN).forEach(([maCauHoi, maDATN]) => {
        const radio = document.querySelector(`input[name="phan1_${maCauHoi}"][value="${maDATN}"]`);
        if (radio) {
            radio.checked = true;
            const label = radio.closest('.option');
            if (label) label.classList.add('selected');
            // mark card answered
            const card = document.getElementById('q' + maCauHoi);
            if (card) card.classList.add('answered');
        }
    });

    // Restore Phần II — DS
    Object.entries(DA_CHON_DS).forEach(([maY, luaChon]) => {
        const radio = document.querySelector(`input[name^="ds_"][name$="_${maY}"][value="${luaChon}"]`);
        if (radio) radio.checked = true;
    });

    // Restore Phần III — Số
    Object.entries(DA_CHON_SO).forEach(([maCauHoi, soHS]) => {
        const input = document.getElementById(`tls_${maCauHoi}`);
        if (input && soHS) {
            input.value = soHS;
            const card = document.getElementById('q' + maCauHoi);
            if (card) card.classList.add('answered');
        }
    });

    // Patch: mỗi khi chọn TN → mark answered + update counter + highlight dot
    document.querySelectorAll('.option input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // highlight option
            const name = this.name; // phan1_MaCauHoi
            document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                const lbl = r.closest('.option');
                if (lbl) lbl.classList.remove('selected');
            });
            const lbl = this.closest('.option');
            if (lbl) lbl.classList.add('selected');

            // mark card
            const card = this.closest('.question-card');
            if (card) card.classList.add('answered');

            updateCounter();
        });
    });

    // Patch: DS radio
    document.querySelectorAll('.ds-table input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const card = this.closest('.question-card');
            if (card) {
                // mark answered only if ALL Y of this card have a selection
                const allRadioNames = new Set(
                    [...card.querySelectorAll('.ds-table input[type="radio"]')].map(r => r.name)
                );
                const answeredNames = new Set(
                    [...card.querySelectorAll('.ds-table input[type="radio"]:checked')].map(r => r.name)
                );
                if (answeredNames.size === allRadioNames.size) {
                    card.classList.add('answered');
                } else {
                    card.classList.add('answered'); // partial ok, still mark
                }
            }
            updateCounter();
        });
    });

    // Patch: TLS number input
    document.querySelectorAll('.tls-input input[type="number"]').forEach(input => {
        input.addEventListener('change', function() {
            const card = this.closest('.question-card');
            if (card && this.value !== '') card.classList.add('answered');
            updateCounter();
        });
    });

    updateCounter();
});

function scrollToQ(n) {
    const cards = document.querySelectorAll('.question-card');
    if (cards[n - 1]) cards[n - 1].scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function updateCounter() {
    const answered  = document.querySelectorAll('.question-card.answered').length;
    const doneEl    = document.getElementById('doneCount');
    const fill      = document.getElementById('progressFill');
    const dots      = document.querySelectorAll('.q-dot');

    if (doneEl) doneEl.textContent = answered;
    if (fill)   fill.style.width = Math.min(100, Math.round(answered / TOTAL_Q * 100)) + '%';

    // sync dots
    document.querySelectorAll('.question-card').forEach((card, i) => {
        if (dots[i]) dots[i].classList.toggle('done', card.classList.contains('answered'));
    });
}
</script>
</body>
</html><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/student/exams/do.blade.php ENDPATH**/ ?>