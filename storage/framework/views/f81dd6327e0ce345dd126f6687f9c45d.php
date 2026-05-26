<?php $__env->startSection('title', 'Đăng ký tài khoản Học sinh'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0 fw-bold">ĐĂNG KÝ HỌC SINH</h4>
            </div>
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="<?php echo e(route('register')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ và tên</label>
                        <input type="text" name="HoTen" class="form-control" placeholder="VD: Đặng Ngọc Toàn" required autofocus>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tên đăng nhập</label>
                            <input type="text" name="TenDangNhap" class="form-control" placeholder="VD: toan_hs" required>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="Email" class="form-control" placeholder="VD: toan@gmail.com" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" placeholder="Tạo mật khẩu" required>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label class="form-label fw-bold">Nhập lại mật khẩu</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Xác nhận mật khẩu" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold fs-5 py-2 shadow-sm">TẠO TÀI KHOẢN</button>
                    
                    <div class="text-center mt-4 border-top pt-3">
                        <span class="text-muted">Đã có tài khoản?</span> 
                        <a href="/login" class="text-decoration-none fw-bold">Đăng nhập tại đây</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\TTCN\Nhom13TTCN\resources\views/auth/register.blade.php ENDPATH**/ ?>