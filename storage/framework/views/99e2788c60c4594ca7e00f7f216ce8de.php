


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - Thi Thử THPT</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
</head>
<body>
    <div class="auth-page">
        <div class="auth-box">

            <div class="auth-header">
                <div class="auth-icon-wrap">🔑</div>
                <h1>Đặt lại mật khẩu</h1>
                <p>Nhập mật khẩu mới của bạn bên dưới</p>
            </div>

            
            <?php if(session('error')): ?>
                <div class="alert alert-error"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.store')); ?>" class="auth-form">
                <?php echo csrf_field(); ?>

                
                <input type="hidden" name="token" value="<?php echo e($token); ?>">

                <div class="auth-field">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo e(old('email', $email)); ?>"
                        placeholder="Nhập email của bạn..."
                        autofocus
                    >
                    <?php $__errorArgs = ['email'];
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
                    <label for="password">Mật khẩu mới</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Ít nhất 6 ký tự..."
                    >
                    <?php $__errorArgs = ['password'];
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
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Nhập lại mật khẩu mới..."
                    >
                    <?php $__errorArgs = ['password_confirmation'];
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

                <button type="submit" class="auth-submit">Đặt lại mật khẩu</button>
            </form>

            <div class="auth-links">
                <a href="<?php echo e(route('login')); ?>">← Quay lại đăng nhập</a>
            </div>
            
        </div>
    </div>
</body>
</html><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/auth/reset-password.blade.php ENDPATH**/ ?>