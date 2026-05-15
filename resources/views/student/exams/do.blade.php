<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $deThi->TenDeThi }}</title>
    <link rel="stylesheet" href="{{ asset('css/exam.css') }}">
</head>
<body>

{{-- Thanh trên cùng: tên đề + đồng hồ --}}
<div class="exam-header">
    <div class="exam-title">{{ $deThi->TenDeThi }}</div>
    <div class="timer" id="timer">
        ⏱ <span id="countdown">{{ $deThi->ThoiGian }}:00</span>
    </div>
    <button class="btn-submit" onclick="xacNhanNopBai()">Nộp bài</button>
</div>

<div class="exam-body">

    {{-- ── PHẦN I: Trắc nghiệm ── --}}
    @if($phan1->count() > 0)
    <div class="phan-title">PHẦN I — Trắc nghiệm ({{ $phan1->count() }} câu × 0.25đ)</div>

    @foreach($phan1 as $i => $cau)
    <div class="question-card" id="q{{ $cau->MaCauHoi }}">
        <div class="question-number">Câu {{ $i + 1 }}</div>

<div class="question-content">
    @if($cau->HinhAnh)
        <img src="{{ asset('storage/' . $cau->HinhAnh) }}" class="img-question" style="max-width: 100%; border-radius: 8px; margin-bottom: 15px;">
    @else
        {!! $cau->NoiDungCH !!}
    @endif
</div>
        <div class="options">
            @foreach($cau->dapAn as $da)
            <label class="option" id="opt_{{ $cau->MaCauHoi }}_{{ $da->MaDATN }}">
                <input type="radio"
                    name="phan1_{{ $cau->MaCauHoi }}"
                    value="{{ $da->MaDATN }}"
                    onchange="luuTN({{ $cau->MaCauHoi }}, {{ $da->MaDATN }}, this)">
                <span class="option-key">{{ $da->KyHieu }}</span>
                <span class="option-text">{{ $da->NoiDungDapAn }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endforeach
    @endif

    {{-- ── PHẦN II: Đúng/Sai ── --}}
{{-- PHẦN II: Đúng/Sai --}}
@if($phan2->count() > 0)
    <div class="phan-title">PHẦN II — Đúng/Sai ({{ $phan2->count() }} câu)</div>

    @foreach($phan2 as $i => $cau)
    <div class="question-card" id="q{{ $cau->MaCauHoi }}">
        <div class="question-number">Câu {{ $i + 1 }}</div>

        {{-- Hiển thị ảnh đề bài chứa nội dung các ý --}}
        @if($cau->HinhAnh)
            <div class="question-image mb-3 text-center">
                <img src="{{ asset('storage/' . $cau->HinhAnh) }}" style="max-width: 100%; border-radius: 8px;">
            </div>
        @endif

        {{-- Bảng chỉ hiện Ý và nút chọn Đúng/Sai --}}
        <table class="ds-table" style="width: 100%; max-width: 400px; margin: 0 auto;">
            <thead>
                <tr>
                    <th class="text-center">Ý</th>
                    <th class="text-center">Đúng</th>
                    <th class="text-center">Sai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cau->cacY as $y)
                <tr>
                    {{-- Hiển thị A, B, C, D dựa trên KyHieu trong DB --}}
                    <td class="text-center"><strong>{{ strtoupper($y->KyHieu) }}</strong></td>
                    <td class="text-center">
                        <input type="radio" 
                               name="ds_{{ $cau->MaCauHoi }}_{{ $y->MaY }}" 
                               value="1" 
                               onchange="luuDS({{ $cau->MaCauHoi }}, {{ $y->MaY }}, 1)">
                    </td>
                    <td class="text-center">
                        <input type="radio" 
                               name="ds_{{ $cau->MaCauHoi }}_{{ $y->MaY }}" 
                               value="0" 
                               onchange="luuDS({{ $cau->MaCauHoi }}, {{ $y->MaY }}, 0)">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
@endif

    {{-- ── PHẦN III: Trả lời ngắn ── --}}
    @if($phan3->count() > 0)
    <div class="phan-title">PHẦN III — Trả lời ngắn ({{ $phan3->count() }} câu × 0.5đ)</div>

    @foreach($phan3 as $i => $cau)
    <div class="question-card" id="q{{ $cau->MaCauHoi }}">
        <div class="question-number">Câu {{ $i + 1 }}</div>

<div class="question-content">
    @if($cau->HinhAnh)
        <img src="{{ asset('storage/' . $cau->HinhAnh) }}" class="img-question" style="max-width: 100%; border-radius: 8px; margin-bottom: 15px;">
    @else
        {!! $cau->NoiDungCH !!}
    @endif
</div>
        <div class="tls-input">
            <label>Đáp án:</label>
            <input type="number"
                step="0.01"
                placeholder="Nhập số..."
                onchange="luuSo({{ $cau->MaCauHoi }}, this.value)"
                id="tls_{{ $cau->MaCauHoi }}">
        </div>
    </div>
    @endforeach
    @endif

    <div class="submit-area">
        <button class="btn-submit-big" onclick="xacNhanNopBai()">
            ✅ Nộp bài
        </button>
    </div>
</div>

{{-- Form nộp bài ẩn --}}
<form id="formNopBai" method="POST" action="{{ route('student.exams.submit') }}">
    @csrf
    <input type="hidden" name="maBaiLam" value="{{ $maBaiLam }}">
</form>

{{-- Truyền biến PHP sang JS an toàn --}}
<script>
    const MA_BAI_LAM     = @json($maBaiLam);
const THOI_GIAN = @json($giayConLai);
    const URL_TN         = @json(route('student.exams.saveTN'));
    const URL_DS         = @json(route('student.exams.saveDS'));
    const URL_SO         = @json(route('student.exams.saveSo'));
    const CSRF_TOKEN     = document.querySelector('meta[name=csrf-token]').content;

    // Đáp án đã lưu từ server
    const DA_CHON_TN = @json($daDuocChonTN); // {maCauHoi: maDATN}
    const DA_CHON_DS = @json($daDuocChonDS); // {maY: luaChon}
    const DA_CHON_SO = @json($daDuocChonSo); // {maCauHoi: soHS}
</script>
<script src="{{ asset('js/exam.js') }}"></script>
<script>
// Restore đáp án sau khi exam.js load xong
document.addEventListener('DOMContentLoaded', function() {

    // Restore Phần I — TN
    Object.entries(DA_CHON_TN).forEach(([maCauHoi, maDATN]) => {
        const radio = document.querySelector(
            `input[name="phan1_${maCauHoi}"][value="${maDATN}"]`
        );
        if (radio) {
            radio.checked = true;
            // Highlight option đã chọn
            const label = radio.closest('.option');
            if (label) label.classList.add('selected');
        }
    });

    // Restore Phần II — DS
    Object.entries(DA_CHON_DS).forEach(([maY, luaChon]) => {
        const radio = document.querySelector(
            `input[name^="ds_"][name$="_${maY}"][value="${luaChon}"]`
        );
        if (radio) radio.checked = true;
    });

    // Restore Phần III — Số
    Object.entries(DA_CHON_SO).forEach(([maCauHoi, soHS]) => {
        const input = document.getElementById(`tls_${maCauHoi}`);
        if (input && soHS) input.value = soHS;
    });
});
</script>
<script src="{{ asset('js/exam.js') }}"></script>
</body>
</html>