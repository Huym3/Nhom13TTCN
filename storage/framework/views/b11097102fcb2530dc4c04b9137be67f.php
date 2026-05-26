<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Thi Thử THPT'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/glass.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">🎓 Thi Thử THPT</div>
        <div class="nav-menu">
            <a href="<?php echo e(route('student.dashboard')); ?>" class="<?php echo e(request()->routeIs('student.dashboard') ? 'active' : ''); ?>">Dashboard</a>
            <a href="<?php echo e(route('student.exams')); ?>"     class="<?php echo e(request()->routeIs('student.exams*') ? 'active' : ''); ?>">Đề Thi</a>
            <a href="<?php echo e(route('student.results.index')); ?>" class="<?php echo e(request()->routeIs('student.results*') ? 'active' : ''); ?>">Kết Quả</a>
        </div>
        <div class="nav-user">
            <span>👤 <?php echo e(session('hoTen')); ?></span>
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-logout">Đăng xuất</button>
            </form>
        </div>
    </nav>

    <main class="main-content">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-error"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/layouts/student.blade.php ENDPATH**/ ?>