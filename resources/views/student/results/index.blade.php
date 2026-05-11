@extends('layouts.student')
@section('title', 'Lịch sử bài làm')

@section('content')
<div class="page-header">
    <h2>📊 Lịch sử bài làm</h2>
</div>

<div class="results-list">
    @forelse($baiLam as $bl)
    <div class="result-card">
        <div class="result-info">
            <h3>{{ $bl->TenDeThi }}</h3>
            <div class="result-meta">
                <span>📅 {{ \Carbon\Carbon::parse($bl->ThoiGianNopBai)->format('d/m/Y H:i') }}</span>
                <span>⏱ {{ gmdate('i:s', $bl->TongThoiGianLamBai) }}</span>
                <span>✅ {{ $bl->SoCauDung }} đúng</span>
                <span>❌ {{ $bl->SoCauSai }} sai</span>
            </div>
            <div class="score-breakdown">
                <span>P.I: {{ number_format($bl->DiemPhan1, 2) }}đ</span>
                <span>P.II: {{ number_format($bl->DiemPhan2, 2) }}đ</span>
                <span>P.III: {{ number_format($bl->DiemPhan3, 2) }}đ</span>
            </div>
        </div>
        <div class="result-right">
            <div class="score-display {{ $bl->TongDiem >= 5 ? 'pass' : 'fail' }}">
                {{ number_format($bl->TongDiem, 2) }}
            </div>
            <a href="{{ route('student.results.show', $bl->MaBaiLam) }}" class="btn-outline">
                Xem chi tiết
            </a>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <p>Bạn chưa làm bài thi nào.</p>
        <a href="{{ route('student.exams') }}" class="btn-primary">Làm bài ngay</a>
    </div>
    @endforelse
</div>
@endsection