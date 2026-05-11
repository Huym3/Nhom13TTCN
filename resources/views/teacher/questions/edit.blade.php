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
    <form method="POST" action="{{ route('teacher.questions.update', $question->MaCauHoi) }}">
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
            <label>Giải thích đáp án</label>
            <textarea name="giaiThich" rows="3" class="form-control">{{ old('giaiThich', $question->GiaiThich) }}</textarea>
        </div>

        {{-- ── Hiển thị đáp án TN ─────────────────── --}}
        @if($question->LoaiCauHoi === 'TN')
        <h3 class="section-title">Đáp án — Trắc nghiệm</h3>
        <div class="alert" style="background:#fef9c3;border:1px solid #fde68a;color:#92400e;margin-bottom:16px;font-size:13px">
            ⚠️ Để sửa đáp án trắc nghiệm, vui lòng xóa câu hỏi này và tạo lại.
        </div>
        <table class="data-table" style="margin-bottom:16px">
            <thead>
                <tr><th width="60">Ký hiệu</th><th>Nội dung</th><th width="100">Đáp án đúng</th></tr>
            </thead>
            <tbody>
                @foreach($dapAnTN as $da)
                <tr>
                    <td class="text-center font-bold">{{ $da->KyHieu }}</td>
                    <td>{{ $da->NoiDungDapAn }}</td>
                    <td class="text-center">
                        @if($da->LaDapAnDung)
                            <span style="color:#16a34a;font-weight:700">✅ Đúng</span>
                        @else
                            <span style="color:#94a3b8">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- ── Hiển thị đáp án DS ─────────────────── --}}
        @if($question->LoaiCauHoi === 'DS')
        <h3 class="section-title">Đáp án — Đúng/Sai</h3>
        <div class="alert" style="background:#fef9c3;border:1px solid #fde68a;color:#92400e;margin-bottom:16px;font-size:13px">
            ⚠️ Để sửa đáp án Đúng/Sai, vui lòng xóa câu hỏi này và tạo lại.
        </div>
        <table class="data-table" style="margin-bottom:16px">
            <thead>
                <tr><th width="60">Ý</th><th>Nội dung</th><th width="100">Đáp án đúng</th></tr>
            </thead>
            <tbody>
                @foreach($cacY as $y)
                <tr>
                    <td class="text-center font-bold">{{ $y->KyHieu }}</td>
                    <td>{{ $y->NoiDungY }}</td>
                    <td class="text-center">
                        @if($y->DapAnDung)
                            <span style="color:#16a34a;font-weight:700">✅ Đúng</span>
                        @else
                            <span style="color:#dc2626;font-weight:700">❌ Sai</span>
                        @endif
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
                <label>Đáp án số hiện tại</label>
                <input type="text" class="form-control" value="{{ $dapAnSo->DapAnSo }}" disabled
                       style="background:#f1f5f9">
            </div>
            <div class="form-group">
                <label>Sai số chấp nhận</label>
                <input type="text" class="form-control" value="{{ $dapAnSo->SaiSoChapNhan }}" disabled
                       style="background:#f1f5f9">
            </div>
            <div class="form-group" style="flex:2">
                <label>Ghi chú</label>
                <input type="text" class="form-control" value="{{ $dapAnSo->GhiChu }}" disabled
                       style="background:#f1f5f9">
            </div>
        </div>
        <div class="alert" style="background:#fef9c3;border:1px solid #fde68a;color:#92400e;margin-bottom:16px;font-size:13px">
            ⚠️ Để sửa đáp án số, vui lòng xóa câu hỏi này và tạo lại.
        </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('teacher.questions.index') }}" class="btn-outline">Hủy</a>
            <button type="submit" class="btn-primary">💾 Lưu thay đổi</button>
        </div>
    </form>
</div>
@endsection