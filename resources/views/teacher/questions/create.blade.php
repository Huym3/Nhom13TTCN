{{-- resources/views/teacher/questions/create.blade.php --}}
@extends('layouts.teacher')
@section('title', 'Tạo câu hỏi mới')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Tạo câu hỏi mới</div>
        <div class="page-sub">Chọn loại câu hỏi để hiển thị form nhập đáp án phù hợp</div>
    </div>
    <a href="{{ route('teacher.questions.index') }}" class="btn-outline">← Quay lại</a>
</div>

@if($errors->any())
    <div class="alert alert-error">
        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
    </div>
@endif

<div class="form-card glass">
    <form method="POST" action="{{ route('teacher.questions.store') }}" id="formCauHoi" enctype="multipart/form-data">
        @csrf

        <h3 class="section-title">Thông tin câu hỏi</h3>

        <div class="form-row">
            <div class="form-group" style="flex: 2;">
                <label>Chuyên đề <span class="required">*</span></label>
                <select name="maChuyenDe" class="form-control">
                    <option value="">-- Chọn chuyên đề --</option>
                    @foreach($chuyenDe as $cd)
                        <option value="{{ $cd->MaChuyenDe }}"
                            {{ old('maChuyenDe') == $cd->MaChuyenDe ? 'selected' : '' }}>
                            {{ $cd->TenChuyenDe }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Độ khó <span class="required">*</span></label>
                <select name="doKho" class="form-control">
                    <option value="">-- Chọn --</option>
                    <option value="De"         {{ old('doKho') === 'De'         ? 'selected' : '' }}>Dễ</option>
                    <option value="Trung Binh" {{ old('doKho') === 'Trung Binh' ? 'selected' : '' }}>Trung Bình</option>
                    <option value="Nang Cao"   {{ old('doKho') === 'Nang Cao'   ? 'selected' : '' }}>Nâng Cao</option>
                </select>
            </div>
            <div class="form-group">
                <label>Loại câu hỏi <span class="required">*</span></label>
                <select name="loaiCauHoi" id="loaiCauHoi" class="form-control">
                    <option value="">-- Chọn loại --</option>
                    <option value="TN"  {{ old('loaiCauHoi') === 'TN'  ? 'selected' : '' }}>Trắc nghiệm (TN)</option>
                    <option value="DS"  {{ old('loaiCauHoi') === 'DS'  ? 'selected' : '' }}>Đúng/Sai (DS)</option>
                    <option value="TLS" {{ old('loaiCauHoi') === 'TLS' ? 'selected' : '' }}>Trả lời số (TLS)</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Nội dung câu hỏi <span class="required">*</span></label>
            <textarea name="noiDung" rows="4" class="form-control"
                      placeholder="Nhập nội dung câu hỏi...">{{ old('noiDung') }}</textarea>
        </div>

        <div class="form-group">
            <label>Ảnh câu hỏi (Chứa đề bài và đáp án)</label>
            <input type="file" name="anhCauHoi" class="form-control" accept="image/*">
            <span class="form-hint">Hệ thống sẽ ưu tiên hiển thị ảnh này thay cho nội dung chữ.</span>
        </div>

        <div class="form-group">
            <label>Giải thích đáp án</label>
            <textarea name="giaiThich" rows="3" class="form-control"
                      placeholder="Giải thích tại sao đáp án đúng (học sinh xem sau khi nộp bài)">{{ old('giaiThich') }}</textarea>
        </div>

        {{-- ── Phần TN ── --}}
        <div id="block-TN" class="dap-an-block" style="display: none;">
            <h3 class="section-title">Đáp án — Trắc nghiệm</h3>
            <div class="form-group" style="max-width: 200px;">
                <label>Đáp án đúng <span class="required">*</span></label>
                <select name="dapAnDung" class="form-control">
                    <option value="">-- Chọn --</option>
                    @foreach(['A','B','C','D'] as $k)
                        <option value="{{ $k }}" {{ old('dapAnDung') === $k ? 'selected' : '' }}>
                            Đáp án {{ $k }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ── Phần DS ── --}}
        <div id="block-DS" class="dap-an-block" style="display: none;">
            <h3 class="section-title">Đáp án — Đúng/Sai (chọn trạng thái cho 4 ý)</h3>
            <div class="ds-grid">
                @foreach(['a','b','c','d'] as $k)
                <div class="ds-item">
                    <div class="ds-label">{{ strtoupper($k) }}</div>
                    <div class="form-group" style="margin-bottom: 0; flex: 1;">
                        <select name="dapAnY_{{ $k }}" class="form-control">
                            <option value="1">✅ Đúng</option>
                            <option value="0">❌ Sai</option>
                        </select>
                    </div>
                    <input type="hidden" name="noiDungY_{{ $k }}" value="Nội dung trong ảnh">
                </div>
                @endforeach
            </div>
        </div>

        {{-- ── Phần TLS ── --}}
        <div id="block-TLS" class="dap-an-block" style="display: none;">
            <h3 class="section-title">Đáp án — Trả lời số</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Đáp án số <span class="required">*</span></label>
                    <input type="number" name="dapAnSo" step="any" class="form-control"
                           value="{{ old('dapAnSo') }}" placeholder="VD: 3.14">
                </div>
                <div class="form-group">
                    <label>Sai số chấp nhận</label>
                    <input type="number" name="saiSo" step="any" class="form-control"
                           value="{{ old('saiSo', '0.005') }}" placeholder="0.005">
                    <span class="form-hint">Câu trả lời trong khoảng ± sai số sẽ được tính đúng</span>
                </div>
                <div class="form-group" style="flex: 2;">
                    <label>Ghi chú đơn vị</label>
                    <input type="text" name="ghiChu" class="form-control"
                           value="{{ old('ghiChu') }}" placeholder="VD: triệu đồng, km/h...">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('teacher.questions.index') }}" class="btn-outline">Hủy</a>
            <button type="submit" class="btn-primary">💾 Lưu câu hỏi</button>
        </div>
    </form>
</div>

<script>
const loaiSelect = document.getElementById('loaiCauHoi');
const blocks     = document.querySelectorAll('.dap-an-block');

function showBlock(loai) {
    blocks.forEach(b => b.style.display = 'none');
    if (loai) {
        const target = document.getElementById('block-' + loai);
        if (target) target.style.display = 'block';
    }
}

loaiSelect.addEventListener('change', () => showBlock(loaiSelect.value));
showBlock('{{ old("loaiCauHoi") }}');
</script>
@endsection