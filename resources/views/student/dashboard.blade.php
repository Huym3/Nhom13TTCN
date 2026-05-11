@extends('layouts.student')
@section('title', 'Dashboard Học Sinh')

@section('content')
<div class="dashboard">
    <h2>Xin chào, {{ session('hoTen') }}! 👋</h2>

    {{-- Thống kê nhanh --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $tongBaiLam }}</div>
            <div class="stat-label">Bài đã làm</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($diemTrungBinh, 2) }}</div>
            <div class="stat-label">Điểm trung bình</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($diemCaoNhat, 2) }}</div>
            <div class="stat-label">Điểm cao nhất</div>
        </div>
    </div>

    <div class="dashboard-grid">
        {{-- Đề chưa làm --}}
        <div class="card">
            <h3>📝 Đề thi có thể làm</h3>
            @forelse($deChuaLam as $de)
            <div class="exam-item">
                <div>
                    <strong>{{ $de->TenDeThi }}</strong>
                    <span class="badge">{{ $de->ThoiGian }} phút</span>
                    <span class="badge">{{ $de->SoCauHoi }} câu</span>
                </div>
                <a href="{{ route('student.exams.start', $de->MaDeThi) }}" class="btn-primary">
                    Làm ngay
                </a>
            </div>
            @empty
            <p class="empty">Bạn đã làm hết các đề thi!</p>
            @endforelse
            <a href="{{ route('student.exams') }}" class="view-all">Xem tất cả →</a>
        </div>

        {{-- Bài làm gần nhất --}}
        <div class="card">
            <h3>📊 Kết quả gần đây</h3>
            @forelse($baiLamGanNhat as $bl)
            <div class="result-item">
                <div>
                    <strong>{{ $bl->TenDeThi }}</strong>
                    <small>{{ \Carbon\Carbon::parse($bl->ThoiGianNopBai)->format('d/m/Y H:i') }}</small>
                </div>
                <div class="score {{ $bl->TongDiem >= 5 ? 'pass' : 'fail' }}">
                    {{ number_format($bl->TongDiem, 2) }}
                </div>
                <a href="{{ route('student.results.show', $bl->MaBaiLam) }}" class="btn-outline">
                    Xem
                </a>
            </div>
            @empty
            <p class="empty">Chưa có bài làm nào.</p>
            @endforelse
            <a href="{{ route('student.results.index') }}" class="view-all">Xem tất cả →</a>
        </div>
    </div>
</div>
@endsection