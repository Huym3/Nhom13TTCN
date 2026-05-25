@extends('layouts.student')
@section('title', 'Danh sách đề thi')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Danh sách đề thi</div>
        <div class="page-sub">Chọn đề thi và bắt đầu luyện tập</div>
    </div>
</div>

<div class="exam-list">
    @forelse($deThi as $de)
    <div class="exam-card glass">
        <div class="exam-info">
            <h3>{{ $de->TenDeThi }}</h3>
            <div class="exam-meta">
                <span>{{ $de->ThoiGian }} phút</span>
                <span>{{ $de->SoCauHoi }} câu</span>
                @if($de->MaDe ?? null)
                <span>Mã: {{ $de->MaDe }}</span>
                @endif
                <span>{{ \Carbon\Carbon::parse($de->NgayTao)->format('d/m/Y') }}</span>
            </div>
        </div>
        <a href="{{ route('student.exams.start', $de->MaDeThi) }}"
           class="btn-primary"
           onclick="return confirm('Bạn có chắc muốn bắt đầu làm bài? Thời gian sẽ tính ngay!')">
            Bắt đầu làm bài
        </a>
    </div>
    @empty
    <div class="empty-state glass" style="padding: 48px;">
        <div class="empty-icon">📄</div>
        <p>Chưa có đề thi nào được đăng.</p>
    </div>
    @endforelse
</div>
@endsection