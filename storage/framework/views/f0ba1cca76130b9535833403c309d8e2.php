
<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="stylesheet" href="<?php echo e(asset('css/glass.css')); ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Teacher'); ?> — Thi Thử THPT</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>
<body>

<nav class="navbar">
    <div class="nav-brand">🎓 Thi Thử THPT — Giáo viên</div>

    <div class="nav-menu">
        <a href="<?php echo e(route('teacher.dashboard')); ?>"
           class="<?php echo e(request()->routeIs('teacher.dashboard') ? 'active' : ''); ?>">
            🏠 Dashboard
        </a>
        <a href="<?php echo e(route('teacher.questions.index')); ?>"
           class="<?php echo e(request()->routeIs('teacher.questions.*') ? 'active' : ''); ?>">
            ❓ Câu hỏi
        </a>
        <a href="<?php echo e(route('teacher.exams.index')); ?>"
           class="<?php echo e(request()->routeIs('teacher.exams.*') ? 'active' : ''); ?>">
            📋 Đề thi
        </a>
    </div>

    <div class="nav-user">
        <span><?php echo e(Session::get('hoTen')); ?></span>
        <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-logout">Đăng xuất</button>
        </form>
    </div>
</nav>

<div class="main-content">
    <?php echo $__env->yieldContent('content'); ?>
</div>

</body>
</html><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/layouts/teacher.blade.php ENDPATH**/ ?>