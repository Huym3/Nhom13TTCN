<?php $__env->startSection('title', 'Đăng nhập'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow border-0 mt-5">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0 fw-bold">ĐĂNG NHẬP</h4>
            </div>
            <div class="card-body p-4">
                
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger px-3 py-2">
                        <ul class="mb-0" style="padding-left: 15px;">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="text-sm"><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên đăng nhập (hoặc Email)</label>
                        <input type="text" name="email" class="form-control" placeholder="Nhập email..." required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu..." required>
                    </div>

                    <div class="text-end mb-3">
                        <a href="/forgot-password" class="text-decoration-none text-muted small">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3 shadow-sm">VÀO THI NGAY</button>
                    
                    <div class="text-center mt-3 border-top pt-3">
                        <span class="text-muted">Chưa có tài khoản?</span>
                        <a href="/register" class="text-decoration-none fw-bold text-primary">Đăng ký ngay</a>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\TTCN\Nhom13TTCN\resources\views/auth/login.blade.php ENDPATH**/ ?>