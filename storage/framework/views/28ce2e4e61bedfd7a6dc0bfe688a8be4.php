
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyệt Giáo Viên - Admin</title>
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

        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-approve {
            background-color: #27ae60;
            color: white;
        }

        .btn-approve:hover {
            background-color: #229954;
        }

        .btn-reject {
            background-color: #e74c3c;
            color: white;
        }

        .btn-reject:hover {
            background-color: #c0392b;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
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
                <li><a href="<?php echo e(route('admin.teachers.pending')); ?>" class="active">👨‍🏫 Duyệt Giáo Viên</a></li>
                <li><a href="<?php echo e(route('admin.users.index')); ?>">👥 Quản Lý Người Dùng</a></li>
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
                <h1>👨‍🏫 Duyệt Tài Khoản Giáo Viên</h1>
                <p style="color: #7f8c8d; margin-top: 5px;">Danh sách giáo viên chờ duyệt</p>
            </div>

            
            <div class="content">
                <?php if(session('success')): ?>
                    <div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <?php if($teachers->count() > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Họ Tên</th>
                                <th>Tên Đăng Nhập</th>
                                <th>Email</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Đăng Ký</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($teacher->MaNguoiDung); ?></td>
                                    <td><?php echo e($teacher->HoTen); ?></td>
                                    <td><?php echo e($teacher->TenDangNhap); ?></td>
                                    <td><?php echo e($teacher->Email); ?></td>
                                    <td>
                                        <span class="badge badge-pending"><?php echo e($teacher->TeacherStatus); ?></span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <form method="POST" action="<?php echo e(route('admin.teachers.approve', $teacher->MaNguoiDung)); ?>" style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <button type="submit" class="btn btn-approve" onclick="return confirm('Duyệt tài khoản giáo viên này?')">✅ Duyệt</button>
                                            </form>
                                            <form method="POST" action="<?php echo e(route('admin.teachers.reject', $teacher->MaNguoiDung)); ?>" style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <button type="submit" class="btn btn-reject" onclick="return confirm('Từ chối tài khoản giáo viên này?')">❌ Từ Chối</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                    
                    <div class="pagination">
                        <?php echo e($teachers->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <h3>😊 Không có giáo viên chờ duyệt</h3>
                        <p>Tất cả tài khoản giáo viên đã được xử lý!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\thuctap\resources\views/admin/teachers/pending.blade.php ENDPATH**/ ?>