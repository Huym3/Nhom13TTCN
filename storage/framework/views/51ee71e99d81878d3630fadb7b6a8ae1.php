


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận OTP - Thi Thử THPT</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
</head>
<body>
    <div class="auth-page">
        <div class="auth-box">
            
            <div class="auth-header">
                <div class="auth-icon-wrap">🛡️</div>
                <h1>Xác nhận mã OTP</h1>
                <p>Chúng tôi đã gửi mã 6 số đến email của bạn.<br>Vui lòng nhập mã để tiếp tục.</p>
            </div>

            
            <?php if(session('error')): ?>
                <div class="alert alert-error"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.verify-otp.post')); ?>" class="auth-form">
                <?php echo csrf_field(); ?>

                <div class="auth-field">
                    <label for="otp">Mã OTP</label>
                    <input
                        type="text"
                        id="otp"
                        name="otp"
                        placeholder="Nhập 6 số OTP..."
                        maxlength="6"
                        pattern="[0-9]{6}"
                        autofocus
                        required
                    >
                    <?php $__errorArgs = ['otp'];
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

                <button type="submit" class="auth-submit">Xác nhận</button>
            </form>

            <div class="auth-links">
                <a href="<?php echo e(route('password.request')); ?>">← Quay lại</a>
            </div>
            
        </div>
    </div>
</body>
</html><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/auth/verify-otp.blade.php ENDPATH**/ ?>