// public/js/exam.js
// Các biến MA_BAI_LAM, THOI_GIAN, URL_TN, URL_DS, URL_SO, CSRF_TOKEN
// được khai báo trong do.blade.php trước khi load file này

// ── Đồng hồ đếm ngược ──────────────────────────────────────
let giayConLai = THOI_GIAN;
const elTimer  = document.getElementById('countdown');

const timer = setInterval(() => {
    giayConLai--;
    const m = String(Math.floor(giayConLai / 60)).padStart(2, '0');
    const s = String(giayConLai % 60).padStart(2, '0');
    elTimer.textContent = m + ':' + s;

    if (giayConLai === 300) {
        elTimer.style.color = 'orange';
        alert('⚠️ Còn 5 phút!');
    }
    if (giayConLai <= 0) {
        clearInterval(timer);
        nopBai();
    }
}, 1000);

// ── Gọi API chung ──────────────────────────────────────────
function callAPI(url, data) {
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify(data)
    });
}

// ── Lưu đáp án TN ──────────────────────────────────────────
function luuTN(maCauHoi, maDATN, el) {
    document.querySelectorAll(`[name="phan1_${maCauHoi}"]`)
        .forEach(r => r.closest('.option').classList.remove('selected'));
    el.closest('.option').classList.add('selected');
    callAPI(URL_TN, { maBaiLam: MA_BAI_LAM, maCauHoi, maDATN });
}

// ── Lưu đáp án Đúng/Sai ────────────────────────────────────
function luuDS(maCauHoi, maY, luaChon) {
    callAPI(URL_DS, { maBaiLam: MA_BAI_LAM, maCauHoi, maY, luaChon });
}

// ── Lưu đáp án số ──────────────────────────────────────────
function luuSo(maCauHoi, soHocSinh) {
    if (soHocSinh === '') return;
    callAPI(URL_SO, { maBaiLam: MA_BAI_LAM, maCauHoi, soHocSinh });
}

// ── Nộp bài ────────────────────────────────────────────────
function xacNhanNopBai() {
    if (confirm('Bạn có chắc muốn nộp bài không?')) {
        clearInterval(timer);
        nopBai();
    }
}

function nopBai() {
    document.getElementById('formNopBai').submit();
}