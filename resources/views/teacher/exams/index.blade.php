{{-- resources/views/teacher/exams/index.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Quản lý đề thi')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h2>📋 Quản lý đề thi</h2>
        <p>Tạo và quản lý đề thi cho học sinh</p>
    </div>
    <a href="{{ route('teacher.exams.create') }}" class="btn-primary">+ Tạo đề mới</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

@if($exams->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">📄</div>
        <p>Bạn chưa có đề thi nào.</p>
        <a href="{{ route('teacher.exams.create') }}" class="btn-primary">Tạo đề thi đầu tiên</a>
    </div>
@else
    <div class="exam-list">
        @foreach($exams as $exam)
        <div class="exam-card">
            <div class="exam-card-left">
                <div class="exam-card-header">
                    <h3>{{ $exam->TenDeThi }}</h3>
                    <span class="badge badge-{{ strtolower($exam->TrangThaiDe) }}">
                        {{ $exam->TrangThaiDe === 'Published' ? '🟢 Đã phát hành' : '⚪ Nháp' }}
                    </span>
                </div>
                <div class="exam-meta">
                    @if($exam->MaDe)
                        <span>🔢 Mã đề: {{ $exam->MaDe }}</span>
                    @endif
                    <span>⏱ {{ $exam->ThoiGian }} phút</span>
                    <span>📝 {{ $exam->SoCauThucTe }} câu hỏi</span>
                    <span>👥 {{ $exam->SoBaiLam }} bài đã nộp</span>
                    <span>📅 {{ \Carbon\Carbon::parse($exam->NgayTao)->format('d/m/Y') }}</span>
                </div>
            </div>
            <div class="exam-card-actions">
                @if($exam->TrangThaiDe === 'Draft')
                    <a href="{{ route('teacher.exams.edit', $exam->MaDeThi) }}" class="btn-outline">✏️ Sửa</a>

                    <form action="{{ route('teacher.exams.publish', $exam->MaDeThi) }}" method="POST" style="display:inline">
                        @csrf @method('PUT')
                        <button type="submit" class="btn-success"
                            onclick="return confirm('Phát hành đề này? Học sinh sẽ có thể làm bài.')">
                            🚀 Phát hành
                        </button>
                    </form>

                    <form action="{{ route('teacher.exams.destroy', $exam->MaDeThi) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger"
                            onclick="return confirm('Xóa đề thi này?')">🗑 Xóa</button>
                    </form>
                @else
                    <a href="{{ route('teacher.exams.stats', $exam->MaDeThi) }}" class="btn-outline">📊 Thống kê</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection