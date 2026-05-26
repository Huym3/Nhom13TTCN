
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu — Thi Thử THPT</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">

        <div class="auth-header">
            <div class="auth-icon-wrap">🔑</div>
            <h1>Quên mật khẩu</h1>
            <p>Nhập email để nhận mã OTP đặt lại mật khẩu</p>
        </div>

        <?php if(session('error')): ?>
            <div class="alert alert-error">⚠️ <?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if(session('success')): ?>
            <div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <form class="auth-form" method="POST" action="<?php echo e(route('password.email')); ?>">
            <?php echo csrf_field(); ?>
            <div class="auth-field">
                <label for="email">Địa chỉ Email</label>
                <input type="email" id="email" name="email"
                       value="<?php echo e(old('email')); ?>"
                       placeholder="email@example.com"
                       autofocus>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="auth-submit">
                Gửi mã OTP →
            </button>
        </form>

        <div class="auth-links">
            <p>Nhớ mật khẩu rồi? <a href="<?php echo e(route('login')); ?>">Đăng nhập</a></p>
        </div>

    </div>
</div>
</body>
</html><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>