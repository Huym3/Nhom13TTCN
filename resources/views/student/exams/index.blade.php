@extends('layouts.student')
@section('title', 'Danh sách đề thi')

@section('content')
<div class="page-header">
    <h2>📝 Danh sách đề thi</h2>
</div>

<div class="exam-list">
    @forelse($deThi as $de)
    <div class="exam-card">
        <div class="exam-info">
            <h3>{{ $de->TenDeThi }}</h3>
            <div class="exam-meta">
                <span>⏱ {{ $de->ThoiGian }} phút</span>
                <span>📋 {{ $de->SoCauHoi }} câu</span>
                <span>🔑 Mã đề: {{ $de->MaDe ?? 'N/A' }}</span>
                <span>📅 {{ \Carbon\Carbon::parse($de->NgayTao)->format('d/m/Y') }}</span>
            </div>
        </div>
        <a href="{{ route('student.exams.start', $de->MaDeThi) }}"
           class="btn-primary"
           onclick="return confirm('Bạn có chắc muốn bắt đầu làm bài? Thời gian sẽ tính ngay!')">
            Bắt đầu làm bài
        </a>
    </div>
    @empty
    <div class="empty-state">
        <p>Chưa có đề thi nào được đăng.</p>
    </div>
    @endforelse
</div>
@endsection