<<<<<<< Updated upstream
{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Thi Thử THPT</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .admin-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .sidebar {
            width: 100%;
            background-color: #2c3e50;
            color: white;
            padding: 0 28px;
            position: sticky;
            height: 60px;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .sidebar-logo {
            font-size: 18px;
            font-weight: bold;
            margin-right: 24px;
            white-space: nowrap;
        }

        .sidebar-menu {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .sidebar-menu li {
            margin-bottom: 0;
        }

        .sidebar-menu a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu a.active {
            background-color: #3498db;
        }

        .main-content {
            flex: 1;
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .sidebar {
                height: auto;
                padding: 12px 16px;
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
            }

            .sidebar-menu {
                width: 100%;
            }
        }

        .header {
            background-color: white;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title h1 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .header-title p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            text-align: right;
        }

        .user-info p {
            font-size: 14px;
            color: #7f8c8d;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .content {
            padding: 30px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }

        .card-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .card-title {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .card-value {
            color: #2c3e50;
            font-size: 32px;
            font-weight: bold;
        }

        .card-value-highlight {
            color: #e74c3c;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .action-btn {
            background-color: #3498db;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
            text-align: center;
            font-weight: 500;
        }

        .action-btn:hover {
            background-color: #2980b9;
        }

        .action-btn.danger {
            background-color: #e74c3c;
        }

        .action-btn.danger:hover {
            background-color: #c0392b;
        }

        .action-btn.success {
            background-color: #27ae60;
        }

        .action-btn.success:hover {
            background-color: #229954;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        {{-- Sidebar --}}
        <div class="sidebar">
            <div class="sidebar-logo">
                ⚙️ ADMIN PANEL
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="active">📊 Dashboard</a></li>
                <li><a href="{{ route('admin.teachers.pending') }}">👨‍🏫 Duyệt Giáo Viên</a></li>
                <li><a href="{{ route('admin.users.index') }}">👥 Quản Lý Người Dùng</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: white; padding: 12px 15px; text-align: left; cursor: pointer; width: 100%; border-radius: 5px; transition: all 0.3s;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'" onmouseout="this.style.backgroundColor='transparent'">🚪 Đăng Xuất</button>
                    </form>
                </li>
            </ul>
        </div>

        {{-- Main Content --}}
        <div class="main-content">
            {{-- Header --}}
            <div class="header">
                <div class="header-title">
                    <h1>Dashboard</h1>
                    <p>Xin chào, {{ Session::get('hoTen') }}!</p>
                </div>
                <div class="header-user">
                    <div class="user-info">
                        <p><strong>{{ Session::get('hoTen') }}</strong></p>
                        <p>Admin</p>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="content">
                @if(session('success'))
                    <div class="alert alert-success">✅ {{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">❌ {{ session('error') }}</div>
                @endif

                <h2 style="margin-bottom: 20px; color: #2c3e50;">Thống Kê Chung</h2>

                {{-- Dashboard Cards --}}
                <div class="dashboard-grid">
                    <div class="card">
                        <div class="card-icon">👥</div>
                        <div class="card-title">Tổng Người Dùng</div>
                        <div class="card-value">{{ $totalUsers }}</div>
                    </div>

                    <div class="card">
                        <div class="card-icon">👨‍🎓</div>
                        <div class="card-title">Học Sinh</div>
                        <div class="card-value">{{ $studentCount }}</div>
                    </div>

                    <div class="card">
                        <div class="card-icon">👨‍🏫</div>
                        <div class="card-title">Giáo Viên</div>
                        <div class="card-value">{{ $teacherCount }}</div>
                    </div>

                    <div class="card">
                        <div class="card-icon">⏳</div>
                        <div class="card-title">Giáo Viên Chờ Duyệt</div>
                        <div class="card-value card-value-highlight">{{ $pendingTeachers }}</div>
                    </div>

                    <div class="card">
                        <div class="card-icon">🚫</div>
                        <div class="card-title">Người Dùng Bị Chặn</div>
                        <div class="card-value">{{ $blockedUsers }}</div>
                    </div>
                </div>

                <h2 style="margin-bottom: 20px; color: #2c3e50; margin-top: 30px;">Hành Động Nhanh</h2>

                <div class="quick-actions">
                    <a href="{{ route('admin.teachers.pending') }}" class="action-btn">
                        👁️ Xem Giáo Viên Chờ Duyệt ({{ $pendingTeachers }})
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="action-btn success">
                        📋 Quản Lý Người Dùng
                    </a>
=======
@extends('layouts.app')

@section('title', 'Admin - Tổng quan Hệ thống')

@section('content')
<div class="container-fluid px-4 pb-5">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <h3 class="fw-bold text-dark mb-0"><i class="bi bi-shield-lock-fill text-danger"></i> TRUNG TÂM QUẢN TRỊ (ADMIN)</h3>
        <span class="text-muted">Cập nhật lúc: {{ date('d/m/Y H:i') }}</span>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-6 fw-bold text-uppercase mb-1">Tổng Học Sinh</div>
                            <div class="fs-2 fw-bold">1,250</div>
                        </div>
                        <i class="bi bi-mortarboard fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-6 fw-bold text-uppercase mb-1">Giáo Viên</div>
                            <div class="fs-2 fw-bold">15</div>
                        </div>
                        <i class="bi bi-person-workspace fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-6 fw-bold text-uppercase mb-1">Đề Thi Hệ Thống</div>
                            <div class="fs-2 fw-bold">120</div>
                        </div>
                        <i class="bi bi-journal-album fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-6 fw-bold text-uppercase mb-1">Tài khoản bị khóa</div>
                            <div class="fs-2 fw-bold">8</div>
                        </div>
                        <i class="bi bi-person-x fs-1 text-white-50"></i>
                    </div>
>>>>>>> Stashed changes
                </div>
            </div>
        </div>
    </div>
<<<<<<< Updated upstream
</body>
</html>
=======

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="fw-bold mb-0">Công cụ Quản trị viên</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="/admin/quan-ly-tai-khoan" class="btn btn-outline-primary text-start fw-bold py-3">
                            <i class="bi bi-people me-2"></i> Quản lý Người dùng (Cấp quyền)
                        </a>
                        <a href="#" class="btn btn-outline-secondary text-start fw-bold py-3">
                            <i class="bi bi-database-gear me-2"></i> Sao lưu Dữ liệu (Backup)
                        </a>
                        <a href="#" class="btn btn-outline-danger text-start fw-bold py-3">
                            <i class="bi bi-gear me-2"></i> Cài đặt Hệ thống
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">Nhật ký Hoạt động (System Logs)</h6>
                    <span class="badge bg-secondary">Hôm nay</span>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <i class="bi bi-person-plus text-success me-2"></i>
                                    <strong>Admin</strong> đã cấp tài khoản Teacher cho <strong>Cô Phương</strong>
                                </div>
                                <span class="text-muted small">10 phút trước</span>
                            </div>
                        </li>
                        <li class="list-group-item py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <i class="bi bi-journal-plus text-primary me-2"></i>
                                    Giáo viên <strong>Thầy Tuấn</strong> vừa xuất bản <strong>Đề thi thử 101</strong>
                                </div>
                                <span class="text-muted small">1 giờ trước</span>
                            </div>
                        </li>
                        <li class="list-group-item py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <i class="bi bi-shield-exclamation text-danger me-2"></i>
                                    Phát hiện đăng nhập sai mật khẩu 5 lần từ tài khoản <strong>toan_hs</strong>
                                </div>
                                <span class="text-muted small">2 giờ trước</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
>>>>>>> Stashed changes
