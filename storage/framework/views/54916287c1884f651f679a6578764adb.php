



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký — Thi Thử THPT</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">

        
        <div class="auth-header">
            <div class="auth-icon-wrap">✏️</div>
            <h1>Tạo tài khoản</h1>
            <p>Tham gia hệ thống thi thử THPT miễn phí</p>
        </div>

        
        <?php if(session('error')): ?>
            <div class="alert alert-error">⚠️ <?php echo e(session('error')); ?></div>
        <?php endif; ?>

        
        <form class="auth-form" method="POST" action="<?php echo e(route('register')); ?>">
            <?php echo csrf_field(); ?>
            
            <input type="hidden" id="roleInput" name="role" value="<?php echo e(old('role', 'Student')); ?>">

            
            <div class="role-selector">
                <div class="role-option">
                    <input type="radio" id="role_student" name="_role_ui"
                           value="Student"
                           <?php echo e(old('role', 'Student') === 'Student' ? 'checked' : ''); ?>>
                    <label for="role_student" onclick="selectRole('Student')">
                        <span class="ri">👨‍🎓</span>
                        Học Sinh
                    </label>
                </div>
                <div class="role-option">
                    <input type="radio" id="role_teacher" name="_role_ui"
                           value="Teacher"
                           <?php echo e(old('role') === 'Teacher' ? 'checked' : ''); ?>>
                    <label for="role_teacher" onclick="selectRole('Teacher')">
                        <span class="ri">👨‍🏫</span>
                        Giáo Viên
                    </label>
                </div>
            </div>

            
            <div class="teacher-warning" id="teacherWarning">
                ⚠️ Tài khoản giáo viên cần được Admin duyệt trước khi đăng nhập được.
            </div>

            
            <div class="auth-field">
                <label for="hoTen">Họ và tên</label>
                <input type="text" id="hoTen" name="hoTen"
                       value="<?php echo e(old('hoTen')); ?>"
                       placeholder="Nguyễn Văn A">
                <?php $__errorArgs = ['hoTen'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="auth-field">
                <label for="tenDangNhap">Tên đăng nhập</label>
                <input type="text" id="tenDangNhap" name="tenDangNhap"
                       value="<?php echo e(old('tenDangNhap')); ?>"
                       placeholder="vidu123">
                <?php $__errorArgs = ['tenDangNhap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="auth-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="<?php echo e(old('email')); ?>"
                       placeholder="example@gmail.com">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="auth-field">
                <label for="ngaySinh">Ngày sinh</label>
                <input type="date" id="ngaySinh" name="ngaySinh"
                       value="<?php echo e(old('ngaySinh')); ?>"
                       max="<?php echo e(now()->toDateString()); ?>">
                <?php $__errorArgs = ['ngaySinh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="auth-field">
                <label for="matKhau">Mật khẩu</label>
                <input type="password" id="matKhau" name="matKhau"
                       placeholder="Ít nhất 6 ký tự">
                <?php $__errorArgs = ['matKhau'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="auth-field">
                <label for="matKhau_confirmation">Xác nhận mật khẩu</label>
                <input type="password" id="matKhau_confirmation"
                       name="matKhau_confirmation"
                       placeholder="Nhập lại mật khẩu">
            </div>

            <button type="submit" class="auth-submit">
                Đăng ký tài khoản →
            </button>
        </form>

        
        <div class="auth-links">
            <p>Đã có tài khoản? <a href="<?php echo e(route('login')); ?>">Đăng nhập</a></p>
        </div>

    </div>
</div>

<script>
function selectRole(role) {
    document.getElementById('roleInput').value = role;

    // Sync radio UI state
    document.querySelectorAll('.role-option input[type="radio"]').forEach(r => {
        r.checked = (r.value === role);
    });

    // Teacher warning
    const warn = document.getElementById('teacherWarning');
    warn.classList.toggle('show', role === 'Teacher');
}

// Init on page load (handles old() value)
(function () {
    const saved = document.getElementById('roleInput').value;
    selectRole(saved);
})();
</script>
</body>
</html><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/auth/register.blade.php ENDPATH**/ ?>