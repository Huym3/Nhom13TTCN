
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Người Dùng - Admin</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/admin.css')); ?>">
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
        }

        .header-title h1 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .content {
            padding: 30px;
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

        .filters {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .filter-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-group input,
        .filter-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .filter-group button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }

        .filter-group button:hover {
            background-color: #2980b9;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #2c3e50;
            color: white;
        }

        .table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .table td {
            padding: 15px;
            border-top: 1px solid #ecf0f1;
        }

        .table tbody tr:hover {
            background-color: #f9f9f9;
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
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
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

        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #2c3e50;
        }

        .pagination a:hover {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }

        .pagination .active {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }

        form {
            display: inline;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        
        <div class="sidebar">
            <div class="sidebar-logo">
                ⚙️ ADMIN PANEL
            </div>
            <ul class="sidebar-menu">
                <li><a href="<?php echo e(route('admin.dashboard')); ?>">📊 Dashboard</a></li>
                <li><a href="<?php echo e(route('admin.teachers.pending')); ?>">👨‍🏫 Duyệt Giáo Viên</a></li>
                <li><a href="<?php echo e(route('admin.users.index')); ?>" class="active">👥 Quản Lý Người Dùng</a></li>
                <li>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" style="background: none; border: none; color: white; padding: 12px 15px; text-align: left; cursor: pointer; width: 100%; border-radius: 5px; transition: all 0.3s;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'" onmouseout="this.style.backgroundColor='transparent'">🚪 Đăng Xuất</button>
                    </form>
                </li>
            </ul>
        </div>

        
        <div class="main-content">
            
            <div class="header">
                <h1>👥 Quản Lý Người Dùng</h1>
                <p style="color: #7f8c8d; margin-top: 5px;">Quản lý tất cả người dùng trong hệ thống</p>
            </div>

            
            <div class="content">
                <?php if(session('success')): ?>
                    <div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
                <?php endif; ?>

                
                <div class="filters">
                    <form method="GET" action="<?php echo e(route('admin.users.index')); ?>">
                        <div class="filter-group">
                            <input type="text" name="search" placeholder="Tìm theo tên, username hoặc email..." value="<?php echo e($search); ?>">
                            <select name="role">
                                <option value="all" <?php echo e($role === 'all' ? 'selected' : ''); ?>>Tất cả vai trò</option>
                                <option value="Student" <?php echo e($role === 'Student' ? 'selected' : ''); ?>>Học Sinh</option>
                                <option value="Teacher" <?php echo e($role === 'Teacher' ? 'selected' : ''); ?>>Giáo Viên</option>
                                <option value="Admin" <?php echo e($role === 'Admin' ? 'selected' : ''); ?>>Admin</option>
                            </select>
                            <select name="status">
                                <option value="all" <?php echo e($status === 'all' ? 'selected' : ''); ?>>Tất cả trạng thái</option>
                                <option value="Active" <?php echo e($status === 'Active' ? 'selected' : ''); ?>>Hoạt Động</option>
                                <option value="Banned" <?php echo e($status === 'Banned' ? 'selected' : ''); ?>>Bị Chặn</option>
                            </select>
                            <button type="submit">🔍 Tìm Kiếm</button>
                        </div>
                    </form>
                </div>

                <?php if($users->count() > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Họ Tên</th>
                                <th>Tên Đăng Nhập</th>
                                <th>Email</th>
                                <th>Vai Trò</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Tạo</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($user->MaNguoiDung); ?></td>
                                    <td><?php echo e($user->HoTen); ?></td>
                                    <td><?php echo e($user->TenDangNhap); ?></td>
                                    <td><?php echo e($user->Email); ?></td>
                                    <td>
                                        <?php if($user->Role === 'Student'): ?>
                                            <span class="badge badge-student">👨‍🎓 Học Sinh</span>
                                        <?php elseif($user->Role === 'Teacher'): ?>
                                            <span class="badge badge-teacher">👨‍🏫 Giáo Viên</span>
                                        <?php else: ?>
                                            <span class="badge badge-admin">⚙️ Admin</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($user->TrangThai === 'Active'): ?>
                                            <span class="badge badge-active">✅ Hoạt Động</span>
                                        <?php else: ?>
                                            <span class="badge badge-blocked">🚫 Bị Chặn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($user->created_at ? $user->created_at->format('d/m/Y') : 'N/A'); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if($user->TrangThai === 'Active' && $user->Role !== 'Admin'): ?>
                                                <form method="POST" action="<?php echo e(route('admin.users.block', $user->MaNguoiDung)); ?>" style="display: inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <button type="submit" class="btn btn-block" onclick="return confirm('Chặn tài khoản này?')">🚫 Chặn</button>
                                                </form>
                                            <?php elseif($user->TrangThai === 'Banned'): ?>
                                                <form method="POST" action="<?php echo e(route('admin.users.unblock', $user->MaNguoiDung)); ?>" style="display: inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <button type="submit" class="btn btn-unblock" onclick="return confirm('Bỏ chặn tài khoản này?')">✅ Bỏ Chặn</button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if($user->Role !== 'Admin'): ?>
                                                <form method="POST" action="<?php echo e(route('admin.users.delete', $user->MaNguoiDung)); ?>" style="display: inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-delete" onclick="return confirm('Xóa tài khoản này? Hành động này không thể hoàn tác!')">❌ Xóa</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                    
                    <div class="pagination">
                        <?php echo e($users->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <h3>😕 Không tìm thấy người dùng</h3>
                        <p>Hãy thử thay đổi bộ lọc tìm kiếm</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\thuctap\resources\views/admin/users/index.blade.php ENDPATH**/ ?>