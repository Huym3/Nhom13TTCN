@extends('layouts.student')
@section('title', 'Dashboard Học Sinh')

@section('content')
<div class="dashboard">

    <div class="page-header" style="border-bottom: none; margin-bottom: 6px;">
        <div>
            <div class="page-title">Xin chào, {{ session('hoTen') }}</div>
            <div class="page-sub">Chào mừng trở lại — hãy tiếp tục luyện tập nhé!</div>
        </div>
    </div>

    {{-- Thống kê nhanh --}}
    <div class="stats-grid" style="margin-bottom: 20px;">
        <div class="stat-card glass">
            <div class="stat-number">{{ $tongBaiLam }}</div>
            <div class="stat-label">Bài đã làm</div>
        </div>
        <div class="stat-card glass">
            <div class="stat-number" style="color: var(--green);">{{ number_format($diemTrungBinh, 2) }}</div>
            <div class="stat-label">Điểm trung bình</div>
        </div>
        <div class="stat-card glass">
            <div class="stat-number" style="color: var(--amber);">{{ number_format($diemCaoNhat, 2) }}</div>
            <div class="stat-label">Điểm cao nhất</div>
        </div>
    </div>

    <div class="dashboard-grid">
        {{-- Đề chưa làm --}}
        <div class="card glass">
            <div class="card-title">Đề thi có thể làm</div>
            @forelse($deChuaLam as $de)
            <div class="exam-item">
                <div>
                    <strong>{{ $de->TenDeThi }}</strong>
                    <small>
                        <span class="badge badge-blue">{{ $de->ThoiGian }} phút</span>
                        &nbsp;
                        <span class="badge badge-blue">{{ $de->SoCauHoi }} câu</span>
                    </small>
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
        <div class="card glass">
            <div class="card-title">Kết quả gần đây</div>
            @forelse($baiLamGanNhat as $bl)
            <div class="result-item">
                <div>
                    <strong>{{ $bl->TenDeThi }}</strong>
                    <small>{{ \Carbon\Carbon::parse($bl->ThoiGianNopBai)->format('d/m/Y H:i') }}</small>
                </div>
                <div style="display:flex; align-items:center; gap: 10px;">
                    <div class="score-display {{ $bl->TongDiem >= 5 ? 'pass' : 'fail' }}" style="font-size:18px;">
                        {{ number_format($bl->TongDiem, 2) }}
                    </div>
                    <a href="{{ route('student.results.show', $bl->MaBaiLam) }}" class="btn-outline">
                        Xem
                    </a>
                </div>
            </div>
            @empty
            <p class="empty">Chưa có bài làm nào.</p>
            @endforelse
            <a href="{{ route('student.results.index') }}" class="view-all">Xem tất cả →</a>
        </div>
    </div>
</div>
@endsection