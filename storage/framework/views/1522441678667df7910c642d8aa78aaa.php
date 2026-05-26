



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập — Thi Thử THPT</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">

        
        <div class="auth-header">
            <div class="auth-icon-wrap">🎓</div>
            <h1>Thi Thử THPT Quốc Gia</h1>
            <p>Đăng nhập để tiếp tục học tập</p>
        </div>

        
        <?php if(session('error')): ?>
            <div class="alert alert-error">⚠️ <?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if(session('success')): ?>
            <div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
        <?php endif; ?>

        
        <form class="auth-form" method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <div class="auth-field">
                <label for="tenDangNhap">Tên đăng nhập</label>
                <input
                    type="text"
                    id="tenDangNhap"
                    name="tenDangNhap"
                    value="<?php echo e(old('tenDangNhap')); ?>"
                    placeholder="Nhập tên đăng nhập của bạn"
                    autocomplete="username"
                    autofocus
                >
                <?php $__errorArgs = ['tenDangNhap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="error-text"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="auth-field">
                <label for="matKhau">Mật khẩu</label>
                <input
                    type="password"
                    id="matKhau"
                    name="matKhau"
                    placeholder="Nhập mật khẩu"
                    autocomplete="current-password"
                >
                <?php $__errorArgs = ['matKhau'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="error-text"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="auth-submit">
                Đăng nhập →
            </button>
        </form>

        
        <div class="auth-links">
            <p>Chưa có tài khoản? <a href="<?php echo e(route('register')); ?>">Đăng ký ngay</a></p>
            <p><a href="<?php echo e(route('password.request')); ?>">Quên mật khẩu?</a></p>
        </div>

    </div>
</div>
</body>
</html><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/auth/login.blade.php ENDPATH**/ ?>