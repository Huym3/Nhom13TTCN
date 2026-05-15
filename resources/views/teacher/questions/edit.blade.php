{{-- resources/views/teacher/questions/edit.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Sửa câu hỏi')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h2>✏️ Sửa câu hỏi</h2>
        <p>Chỉnh sửa nội dung câu hỏi — loại câu hỏi không thể thay đổi</p>
    </div>
    <a href="{{ route('teacher.questions.index') }}" class="btn-outline">← Quay lại</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-error">
        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
    </div>
@endif

<div class="form-card">
<form method="POST" action="{{ route('teacher.questions.update', $question->MaCauHoi) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <h3 class="section-title">Thông tin câu hỏi</h3>

        <div class="form-row">
            <div class="form-group" style="flex:2">
                <label>Chuyên đề</label>
                <select name="maChuyenDe" class="form-control">
                    @foreach($chuyenDe as $cd)
                        <option value="{{ $cd->MaChuyenDe }}"
                            {{ $question->MaChuyenDe == $cd->MaChuyenDe ? 'selected' : '' }}>
                            {{ $cd->TenChuyenDe }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Độ khó</label>
                <select name="doKho" class="form-control">
                    <option value="De"         {{ $question->DoKho === 'De'         ? 'selected' : '' }}>Dễ</option>
                    <option value="Trung Binh" {{ $question->DoKho === 'Trung Binh' ? 'selected' : '' }}>Trung Bình</option>
                    <option value="Nang Cao"   {{ $question->DoKho === 'Nang Cao'   ? 'selected' : '' }}>Nâng Cao</option>
                </select>
            </div>
            <div class="form-group">
                <label>Loại câu hỏi</label>
                <input type="text" class="form-control"
                       value="{{ $question->LoaiCauHoi }}" disabled
                       style="background:#f1f5f9;cursor:not-allowed">
                <small class="form-hint">Không thể thay đổi loại câu hỏi</small>
            </div>
        </div>

        <div class="form-group">
            <label>Nội dung câu hỏi</label>
            <textarea name="noiDung" rows="4" class="form-control">{{ old('noiDung', $question->NoiDungCH) }}</textarea>
        </div>

        <div class="form-group">
    <label>Hình ảnh câu hỏi</label>
    @if($question->HinhAnh)
        <div style="margin: 10px 0; border: 1px solid #ddd; padding: 5px; width: fit-content; border-radius: 8px;">
            <img src="{{ asset('storage/' . $question->HinhAnh) }}" style="max-width: 250px; display: block;">
        </div>
    @endif
    <input type="file" name="anhCauHoi" class="form-control" accept="image/*">
    <small class="form-hint">Tải ảnh mới lên nếu muốn thay đổi ảnh cũ.</small>
</div>

        <div class="form-group">
            <label>Giải thích đáp án</label>
            <textarea name="giaiThich" rows="3" class="form-control">{{ old('giaiThich', $question->GiaiThich) }}</textarea>
        </div>

{{-- ── Cập nhật đáp án TN ─────────────────── --}}
@if($question->LoaiCauHoi === 'TN')
<h3 class="section-title">Đáp án — Trắc nghiệm</h3>

<div class="form-group" style="max-width: 250px;">
    <label>Chọn đáp án đúng <span class="required">*</span></label>
    <select name="dapAnDung" class="form-control">
        @foreach($dapAnTN as $da)
            <option value="{{ $da->KyHieu }}" {{ $da->LaDapAnDung ? 'selected' : '' }}>
                Đáp án {{ $da->KyHieu }}
            </option>
        @endforeach
    </select>
</div>

@endif

{{-- resources/views/teacher/questions/edit.blade.php --}}

@if($question->LoaiCauHoi === 'DS')
<h3 class="section-title">Chỉnh sửa đáp án Đúng/Sai</h3>
<table class="data-table">
    <thead>
        <tr>
            <th width="100">Ký hiệu</th>
            <th>Trạng thái đáp án</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cacY as $y)
        <tr>
            <td class="text-center font-bold" style="font-size: 1.2rem;">{{ strtoupper($y->KyHieu) }}</td>
            <td>
                <select name="dapAnY_{{ $y->KyHieu }}" class="form-control">
                    <option value="1" {{ $y->DapAnDung == 1 ? 'selected' : '' }}>✅ Đúng</option>
                    <option value="0" {{ $y->DapAnDung == 0 ? 'selected' : '' }}>❌ Sai</option>
                </select>
                <input type="hidden" name="noiDungY_{{ $y->KyHieu }}" value="{{ $y->NoiDungY }}">
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
        {{-- ── Hiển thị đáp án TLS ────────────────── --}}
        @if($question->LoaiCauHoi === 'TLS' && $dapAnSo)
        <h3 class="section-title">Đáp án — Trả lời số</h3>
        <div class="form-row">
<div class="form-group">
    <label>Đáp án số</label>
    <input type="number" step="any" name="dapAnSo" class="form-control"
           value="{{ old('dapAnSo', $dapAnSo->DapAnSo) }}">
</div>
<div class="form-group">
    <label>Sai số chấp nhận</label>
    <input type="number" step="any" name="saiSo" class="form-control"
           value="{{ old('saiSo', $dapAnSo->SaiSoChapNhan) }}">
</div>
        @endif

        <div class="form-actions">
            <a href="{{ route('teacher.questions.index') }}" class="btn-outline">Hủy</a>
            <button type="submit" class="btn-primary">💾 Lưu thay đổi</button>
        </div>
    </form>
</div>
@endsection