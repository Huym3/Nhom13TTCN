<<<<<<< Updated upstream
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
=======
@extends('layouts.app')

@section('title', 'Lịch sử bài làm')

@section('content')
<div class="container pb-5">
    
    <h3 class="fw-bold mb-4"><i class="bi bi-person-lines-fill text-primary"></i> LỊCH SỬ BÀI LÀM CỦA TÔI</h3>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Tên đề thi</th>
                            <th>Thời gian nộp bài</th>
                            <th>Thời gian làm</th>
                            <th>Điểm số</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">1</td>
                            <td>
                                <div class="fw-bold text-dark">Đề thi thử THPT Quốc gia 2025 - Cụm Nam Định</div>
                                <span class="badge bg-light text-dark border">Mã đề: 101</span>
                            </td>
                            <td class="text-muted">14:30 - 08/05/2026</td>
                            <td>85 phút</td>
                            <td><span class="badge bg-success fs-6">8.60</span></td>
                            <td class="text-center">
                                <a href="/ket-qua" class="btn btn-sm btn-primary px-3">Xem đáp án & Bài làm</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">2</td>
                            <td>
                                <div class="fw-bold text-dark">Kiểm tra giữa kì 2 - Lớp 12</div>
                                <span class="badge bg-light text-dark border">Mã đề: 204</span>
                            </td>
                            <td class="text-muted">09:15 - 05/05/2026</td>
                            <td>45 phút</td>
                            <td><span class="badge bg-warning text-dark fs-6">6.25</span></td>
                            <td class="text-center">
                                <a href="/ket-qua" class="btn btn-sm btn-primary px-3">Xem đáp án & Bài làm</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
>>>>>>> Stashed changes
        </div>
    </div>
</div>
@endsection