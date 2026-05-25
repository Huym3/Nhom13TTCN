@extends('layouts.student')
@section('title', 'Lịch sử bài làm')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Lịch sử bài làm</div>
        <div class="page-sub">Tổng hợp tất cả các bài thi bạn đã hoàn thành</div>
    </div>
</div>

<div class="results-list">
    @forelse($baiLam as $bl)
    <div class="result-card glass">
        <div class="result-info">
            <h3>{{ $bl->TenDeThi }}</h3>
            <div class="result-meta">
                <span>{{ \Carbon\Carbon::parse($bl->ThoiGianNopBai)->format('d/m/Y H:i') }}</span>
                <span>{{ gmdate('i:s', $bl->TongThoiGianLamBai) }}</span>
                <span style="color: var(--green);">{{ $bl->SoCauDung }} đúng</span>
                <span style="color: var(--red);">{{ $bl->SoCauSai }} sai</span>
            </div>
            <div class="score-breakdown">
                <span>P.I: <strong style="color: var(--text-primary);">{{ number_format($bl->DiemPhan1, 2) }}đ</strong></span>
                <span>P.II: <strong style="color: var(--text-primary);">{{ number_format($bl->DiemPhan2, 2) }}đ</strong></span>
                <span>P.III: <strong style="color: var(--text-primary);">{{ number_format($bl->DiemPhan3, 2) }}đ</strong></span>
            </div>
        </div>
        <div class="result-right">
            <div class="score-display {{ $bl->TongDiem >= 5 ? 'score-pass' : 'score-fail' }}">
                {{ number_format($bl->TongDiem, 2) }}
            </div>
            <a href="{{ route('student.results.show', $bl->MaBaiLam) }}" class="btn-outline">
                Xem chi tiết
            </a>
        </div>
    </div>
    @empty
    <div class="empty-state glass" style="padding: 48px;">
        <div class="empty-icon">📋</div>
        <p>Bạn chưa làm bài thi nào.</p>
        <a href="{{ route('student.exams') }}" class="btn-primary">Làm bài ngay</a>
    </div>
    @endforelse
</div>
@endsection