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

// ── Lưu đáp án TN (Cập nhật hiệu ứng cho nút bấm) ────────────────
function luuTN(maCauHoi, maDATN, el) {
    // Tìm tất cả các nút hoặc label trong cùng một câu hỏi để xóa class 'selected'
    const parent = el.closest('.question-card');
    parent.querySelectorAll('.option').forEach(opt => opt.classList.remove('selected'));
    
    // Thêm class 'selected' cho lựa chọn vừa bấm
    el.closest('.option').classList.add('selected');

    // Gọi API lưu vào Database (gọi Procedure sp_LuuTraLoiTN)
    callAPI(URL_TN, { 
        maBaiLam: MA_BAI_LAM, 
        maCauHoi: maCauHoi, 
        maDATN: maDATN 
    });
}

// ── Lưu đáp án Đúng/Sai (Giữ nguyên hoặc thêm feedback) ──────────
function luuDS(maCauHoi, maY, luaChon) {
    // Có thể thêm hiệu ứng màu sắc cho hàng vừa chọn để học sinh dễ theo dõi
    callAPI(URL_DS, { 
        maBaiLam: MA_BAI_LAM, 
        maCauHoi: maCauHoi, 
        maY: maY, 
        luaChon: luaChon 
    });
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