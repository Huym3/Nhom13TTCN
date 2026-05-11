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
        <div class="question-content">{!! $cau->NoiDungCH !!}</div>
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
    @if($phan2->count() > 0)
    <div class="phan-title">PHẦN II — Đúng/Sai ({{ $phan2->count() }} câu × tối đa 1đ)</div>

    @foreach($phan2 as $i => $cau)
    <div class="question-card" id="q{{ $cau->MaCauHoi }}">
        <div class="question-number">Câu {{ $i + 1 }}</div>
        <div class="question-content">{!! $cau->NoiDungCH !!}</div>
        <table class="ds-table">
            <thead>
                <tr><th>Ý</th><th>Nội dung</th><th>Đúng</th><th>Sai</th></tr>
            </thead>
            <tbody>
                @foreach($cau->cacY as $y)
                <tr>
                    <td><strong>{{ $y->KyHieu }}</strong></td>
                    <td>{{ $y->NoiDungY }}</td>
                    <td>
                        <input type="radio"
                            name="ds_{{ $cau->MaCauHoi }}_{{ $y->MaY }}"
                            value="1"
                            onchange="luuDS({{ $cau->MaCauHoi }}, {{ $y->MaY }}, 1)">
                    </td>
                    <td>
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
        <div class="question-content">{!! $cau->NoiDungCH !!}</div>
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
    const MA_BAI_LAM = @json($maBaiLam);
    const THOI_GIAN  = @json($deThi->ThoiGian * 60);
    const URL_TN     = @json(route('student.exams.saveTN'));
    const URL_DS     = @json(route('student.exams.saveDS'));
    const URL_SO     = @json(route('student.exams.saveSo'));
    const CSRF_TOKEN = document.querySelector('meta[name=csrf-token]').content;
</script>
<script src="{{ asset('js/exam.js') }}"></script>
</body>
</html>