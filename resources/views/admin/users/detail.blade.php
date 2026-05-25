{{-- resources/views/admin/users/detail.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Người Dùng - Admin</title>
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

        .sidebar-menu a, .sidebar-menu form button {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover, .sidebar-menu form button:hover {
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

        .back-btn {
            background-color: #95a5a6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #7f8c8d;
        }

        .content {
            padding: 30px;
        }

        .detail-card {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            padding: 15px 0;
            border-bottom: 1px solid #ecf0f1;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .detail-value {
            color: #7f8c8d;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-active {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-blocked {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-student {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .badge-teacher {
            background-color: #e7d4f5;
            color: #721c94;
        }

        .badge-admin {
            background-color: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-block {
            background-color: #f39c12;
            color: white;
        }

        .btn-block:hover {
            background-color: #e67e22;
        }

        .btn-unblock {
            background-color: #27ae60;
            color: white;
        }

        .btn-unblock:hover {
            background-color: #229954;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        form {
            display: inline;
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
                <li><a href="{{ route('admin.dashboard') }}">📊 Dashboard</a></li>
                <li><a href="{{ route('admin.teachers.pending') }}">👨‍🏫 Duyệt Giáo Viên</a></li>
                <li><a href="{{ route('admin.users.index') }}" class="active">👥 Quản Lý Người Dùng</a></li>
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
                <div>
                    <h1>Chi Tiết Người Dùng</h1>
                    <p style="color: #7f8c8d; margin-top: 5px;">{{ $user->HoTen }}</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="back-btn">← Quay Lại</a>
            </div>

            {{-- Content --}}
            <div class="content">
                <div class="detail-card">
                    <h2 style="margin-bottom: 20px; color: #2c3e50;">📋 Thông Tin Cá Nhân</h2>

                    <div class="detail-row">
                        <div class="detail-label">Mã Người Dùng:</div>
                        <div class="detail-value">{{ $user->MaNguoiDung }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Họ và Tên:</div>
                        <div class="detail-value">{{ $user->HoTen }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Tên Đăng Nhập:</div>
                        <div class="detail-value">{{ $user->TenDangNhap }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Email:</div>
                        <div class="detail-value">{{ $user->Email }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Ngày Sinh:</div>
                        <div class="detail-value">
                            @if($user->NgaySinh)
                                {{ \Carbon\Carbon::parse($user->NgaySinh)->format('d/m/Y') }}
                            @else
                                <em>Chưa cập nhật</em>
                            @endif
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Vai Trò:</div>
                        <div class="detail-value">
                            @if($user->Role === 'Student')
                                <span class="badge badge-student">👨‍🎓 Học Sinh</span>
                            @elseif($user->Role === 'Teacher')
                                <span class="badge badge-teacher">👨‍🏫 Giáo Viên</span>
                            @else
                                <span class="badge badge-admin">⚙️ Admin</span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Trạng Thái Tài Khoản:</div>
                        <div class="detail-value">
                            @if($user->TrangThai === 'Active')
                                <span class="badge badge-active">✅ Hoạt Động</span>
                            @else
                                <span class="badge badge-blocked">🚫 Bị Chặn</span>
                            @endif
                        </div>
                    </div>

                    @if($user->Role === 'Teacher')
                        <div class="detail-row">
                            <div class="detail-label">Trạng Thái Duyệt:</div>
                            <div class="detail-value">
                                @if($user->TeacherStatus === 'Approved')
                                    <span class="badge" style="background-color: #d4edda; color: #155724;">✅ Đã Duyệt</span>
                                @elseif($user->TeacherStatus === 'Pending')
                                    <span class="badge" style="background-color: #fff3cd; color: #856404;">⏳ Chờ Duyệt</span>
                                @else
                                    <span class="badge" style="background-color: #f8d7da; color: #721c24;">❌ Bị Từ Chối</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="detail-row">
                        <div class="detail-label">Ngày Tạo:</div>
                        <div class="detail-value">{{ $user->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Cập Nhật Lần Cuối:</div>
                        <div class="detail-value">{{ $user->updated_at->format('d/m/Y H:i:s') }}</div>
                    </div>

                    {{-- Action Buttons --}}
                    @if($user->Role !== 'Admin')
                        <div class="action-buttons">
                            @if($user->TrangThai === 'Active')
                                <form method="POST" action="{{ route('admin.users.block', $user->MaNguoiDung) }}" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-block" onclick="return confirm('Chặn tài khoản này?')">🚫 Chặn Tài Khoản</button>
                                </form>
                            @elseif($user->TrangThai === 'Banned')
                                <form method="POST" action="{{ route('admin.users.unblock', $user->MaNguoiDung) }}" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-unblock" onclick="return confirm('Bỏ chặn tài khoản này?')">✅ Bỏ Chặn Tài Khoản</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.users.delete', $user->MaNguoiDung) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete" onclick="return confirm('Xóa tài khoản này? Hành động này không thể hoàn tác!')">❌ Xóa Tài Khoản</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
