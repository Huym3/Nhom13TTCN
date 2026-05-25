{{-- resources/views/admin/exams/index.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đề Thi - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; }
        .admin-container { display: flex; flex-direction: column; min-height: 100vh; }
        .sidebar {
            width: 100%; background-color: #2c3e50; color: white; padding: 0 28px;
            position: sticky; height: 60px; top: 0; z-index: 100; display: flex;
            align-items: center; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }
        .sidebar-logo { font-size: 18px; font-weight: bold; margin-right: 24px; white-space: nowrap; }
        .sidebar-menu { list-style: none; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .sidebar-menu li { margin-bottom: 0; }
        .sidebar-menu a, .sidebar-menu form button {
            color: white; text-decoration: none; display: block; padding: 12px 15px;
            border-radius: 5px; transition: all .3s;
        }
        .sidebar-menu a:hover, .sidebar-menu form button:hover { background-color: rgba(255,255,255,.1); }
        .sidebar-menu a.active { background-color: #3498db; }
        .main-content { flex: 1; overflow-y: auto; }
        .header { background: white; padding: 20px; border-bottom: 1px solid #ddd; }
        .header h1 { color: #2c3e50; margin-bottom: 5px; }
        .header p { color: #7f8c8d; margin-top: 5px; }
        .content { padding: 30px; }
        .alert { padding: 15px 20px; border-radius: 5px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .filters { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,.1); }
        .filter-group { display: grid; grid-template-columns: minmax(240px, 1fr) 200px 130px; gap: 15px; }
        .filter-group input, .filter-group select {
            padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;
        }
        .filter-group button {
            background: #3498db; color: white; border: none; padding: 10px 20px;
            border-radius: 5px; cursor: pointer; font-weight: 600;
        }
        .filter-group button:hover { background: #2980b9; }
        .table-wrap { overflow-x: auto; background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,.1); }
        .table { width: 100%; border-collapse: collapse; min-width: 980px; }
        .table thead { background: #2c3e50; color: white; }
        .table th, .table td { padding: 14px 15px; text-align: left; border-top: 1px solid #ecf0f1; vertical-align: middle; }
        .table th { border-top: none; font-weight: 600; }
        .table tbody tr:hover { background: #f9f9f9; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-published { background: #d4edda; color: #155724; }
        .badge-draft { background: #e9ecef; color: #495057; }
        .btn-delete {
            background: #e74c3c; color: white; border: none; padding: 8px 12px;
            border-radius: 5px; cursor: pointer; font-size: 12px; font-weight: 600;
        }
        .btn-delete:hover { background: #c0392b; }
        .pagination { display: flex; justify-content: center; gap: 5px; margin-top: 20px; }
        .pagination a, .pagination span {
            padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px;
            text-decoration: none; color: #2c3e50;
        }
        .pagination .active, .pagination a:hover { background: #3498db; color: white; border-color: #3498db; }
        .no-data { text-align: center; padding: 40px; color: #7f8c8d; background: white; border-radius: 8px; }
        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(0,0,0,.5); display: flex;
            align-items: center; justify-content: center; z-index: 9999; padding: 20px;
        }
        .modal {
            background: white; border-radius: 12px; padding: 32px; max-width: 480px;
            width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,.3); text-align: center;
        }
        .modal h3 { color: #dc2626; margin-bottom: 10px; }
        .modal p { color: #64748b; margin-bottom: 10px; line-height: 1.5; }
        .modal-actions { display: flex; gap: 12px; justify-content: center; margin-top: 22px; }
        .btn-cancel { padding: 10px 24px; border: 1px solid #cbd5e1; border-radius: 8px; background: white; cursor: pointer; }
        .btn-confirm { padding: 10px 24px; background: #dc2626; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; }
        @media (max-width: 768px) {
            .sidebar { height: auto; padding: 12px 16px; align-items: flex-start; flex-direction: column; gap: 12px; }
            .sidebar-menu { width: 100%; }
            .filter-group { grid-template-columns: 1fr; }
            .content { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-logo">⚙️ ADMIN PANEL</div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}">📊 Dashboard</a></li>
                <li><a href="{{ route('admin.teachers.pending') }}">👨‍🏫 Duyệt Giáo Viên</a></li>
                <li><a href="{{ route('admin.exams.index') }}" class="active">📝 Quản Lý Đề Thi</a></li>
                <li><a href="{{ route('admin.users.index') }}">👥 Quản Lý Người Dùng</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" style="background:none;border:none;cursor:pointer">🚪 Đăng Xuất</button>
                    </form>
                </li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>📝 Quản Lý Đề Thi</h1>
                <p>Xem và xóa các đề thi có trên hệ thống</p>
            </div>

            <div class="content">
                @if(session('success'))
                    <div class="alert alert-success">✅ {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">❌ {{ session('error') }}</div>
                @endif

                @if(session('confirm_delete_exam'))
                    <div id="modalXoaDe" class="modal-backdrop">
                        <div class="modal">
                            <div style="font-size:48px;margin-bottom:16px">⚠️</div>
                            <h3>Cảnh báo xóa dữ liệu</h3>
                            <p>
                                Đề thi <strong>{{ session('ten_de_thi') }}</strong> đang có
                                <strong style="color:#dc2626">{{ session('so_bai_lam') }} bài làm</strong>.
                            </p>
                            <p>Toàn bộ kết quả, điểm số và lịch sử làm bài sẽ bị xóa vĩnh viễn.</p>
                            <div class="modal-actions">
                                <button class="btn-cancel" onclick="document.getElementById('modalXoaDe').remove()">Hủy bỏ</button>
                                <form action="{{ route('admin.exams.delete', session('confirm_delete_exam')) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <input type="hidden" name="confirmed" value="1">
                                    <button type="submit" class="btn-confirm">Xóa tất cả</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="filters">
                    <form method="GET" action="{{ route('admin.exams.index') }}">
                        <div class="filter-group">
                            <input type="text" name="search" placeholder="Tìm theo tên đề, mã đề hoặc giáo viên..." value="{{ $search }}">
                            <select name="status">
                                <option value="all" {{ $status === 'all' || !$status ? 'selected' : '' }}>Tất cả trạng thái</option>
                                <option value="Draft" {{ $status === 'Draft' ? 'selected' : '' }}>Nháp</option>
                                <option value="Published" {{ $status === 'Published' ? 'selected' : '' }}>Đã phát hành</option>
                            </select>
                            <button type="submit">Tìm kiếm</button>
                        </div>
                    </form>
                </div>

                @if($exams->count() > 0)
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên đề thi</th>
                                    <th>Mã đề</th>
                                    <th>Giáo viên tạo</th>
                                    <th>Thời gian</th>
                                    <th>Câu hỏi</th>
                                    <th>Bài làm</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($exams as $exam)
                                    <tr>
                                        <td>{{ $exam->MaDeThi }}</td>
                                        <td>{{ $exam->TenDeThi }}</td>
                                        <td>{{ $exam->MaDe ?: 'N/A' }}</td>
                                        <td>{{ $exam->TenGiaoVien ?: 'Không rõ' }}</td>
                                        <td>{{ $exam->ThoiGian }} phút</td>
                                        <td>{{ $exam->SoCauThucTe }}</td>
                                        <td>{{ $exam->SoBaiLam }}</td>
                                        <td>
                                            @if($exam->TrangThaiDe === 'Published')
                                                <span class="badge badge-published">Đã phát hành</span>
                                            @else
                                                <span class="badge badge-draft">Nháp</span>
                                            @endif
                                        </td>
                                        <td>{{ $exam->NgayTao ? \Carbon\Carbon::parse($exam->NgayTao)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.exams.delete', $exam->MaDeThi) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-delete"
                                                    onclick="return confirm('Xóa đề thi này? Nếu đề đã có bài làm, hệ thống sẽ hỏi xác nhận thêm trước khi xóa dữ liệu.')">
                                                    Xóa
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination">{{ $exams->links() }}</div>
                @else
                    <div class="no-data">
                        <h3>Không tìm thấy đề thi</h3>
                        <p>Hãy thử thay đổi bộ lọc tìm kiếm.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
