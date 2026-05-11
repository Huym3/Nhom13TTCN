{{-- resources/views/teacher/exams/create.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Tạo đề thi mới')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h2>➕ Tạo đề thi mới</h2>
        <p>Điền thông tin cơ bản — câu hỏi thêm sau ở bước tiếp theo</p>
    </div>
    <a href="{{ route('teacher.exams.index') }}" class="btn-outline">← Quay lại</a>
</div>

@if($errors->any())
    <div class="alert alert-error">
        @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
    </div>
@endif

<div class="form-card">
    <form method="POST" action="{{ route('teacher.exams.store') }}">
        @csrf

        <div class="form-group">
            <label for="tenDeThi">Tên đề thi <span class="required">*</span></label>
            <input type="text" id="tenDeThi" name="tenDeThi"
                   value="{{ old('tenDeThi') }}"
                   placeholder="VD: Đề thi thử THPT lần 1 - Toán 2026"
                   class="form-control">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="maDe">Mã đề</label>
                <input type="text" id="maDe" name="maDe"
                       value="{{ old('maDe') }}"
                       placeholder="VD: 0018"
                       class="form-control">
                <small class="form-hint">Để trống nếu chưa có mã đề cụ thể</small>
            </div>

            <div class="form-group">
                <label for="thoiGian">Thời gian làm bài <span class="required">*</span></label>
                <select id="thoiGian" name="thoiGian" class="form-control">
                    <option value="">-- Chọn thời gian --</option>
                    @foreach([15 => '15 phút', 45 => '45 phút', 60 => '60 phút', 90 => '90 phút'] as $val => $label)
                        <option value="{{ $val }}" {{ old('thoiGian') == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="cauTruc">Mô tả cấu trúc đề</label>
            <textarea id="cauTruc" name="cauTruc" rows="3"
                      class="form-control"
                      placeholder="VD: Phần I (12 câu TN × 0.25đ) + Phần II (4 câu DS × 1đ) + Phần III (6 câu TLS × 0.5đ)">{{ old('cauTruc') }}</textarea>
        </div>

        <div class="form-actions">
            <a href="{{ route('teacher.exams.index') }}" class="btn-outline">Hủy</a>
            <button type="submit" class="btn-primary">Tạo đề thi →</button>
        </div>
    </form>
</div>
@endsection