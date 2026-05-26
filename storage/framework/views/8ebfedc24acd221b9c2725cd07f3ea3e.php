<?php $__env->startSection('title', 'Dashboard Học Sinh'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard">

    <div class="page-header" style="border-bottom: none; margin-bottom: 6px;">
        <div>
            <div class="page-title">Xin chào, <?php echo e(session('hoTen')); ?></div>
            <div class="page-sub">Chào mừng trở lại — hãy tiếp tục luyện tập nhé!</div>
        </div>
    </div>

    
    <div class="stats-grid" style="margin-bottom: 20px;">
        <div class="stat-card glass">
            <div class="stat-number"><?php echo e($tongBaiLam); ?></div>
            <div class="stat-label">Bài đã làm</div>
        </div>
        <div class="stat-card glass">
            <div class="stat-number" style="color: var(--green);"><?php echo e(number_format($diemTrungBinh, 2)); ?></div>
            <div class="stat-label">Điểm trung bình</div>
        </div>
        <div class="stat-card glass">
            <div class="stat-number" style="color: var(--amber);"><?php echo e(number_format($diemCaoNhat, 2)); ?></div>
            <div class="stat-label">Điểm cao nhất</div>
        </div>
    </div>

    <div class="dashboard-grid">
        
        <div class="card glass">
            <div class="card-title">Đề thi có thể làm</div>
            <?php $__empty_1 = true; $__currentLoopData = $deChuaLam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $de): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="exam-item">
                <div>
                    <strong><?php echo e($de->TenDeThi); ?></strong>
                    <small>
                        <span class="badge badge-blue"><?php echo e($de->ThoiGian); ?> phút</span>
                        &nbsp;
                        <span class="badge badge-blue"><?php echo e($de->SoCauHoi); ?> câu</span>
                    </small>
                </div>
                <a href="<?php echo e(route('student.exams.start', $de->MaDeThi)); ?>" class="btn-primary">
                    Làm ngay
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="empty">Bạn đã làm hết các đề thi!</p>
            <?php endif; ?>
            <a href="<?php echo e(route('student.exams')); ?>" class="view-all">Xem tất cả →</a>
        </div>

        
        <div class="card glass">
            <div class="card-title">Kết quả gần đây</div>
            <?php $__empty_1 = true; $__currentLoopData = $baiLamGanNhat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="result-item">
                <div>
                    <strong><?php echo e($bl->TenDeThi); ?></strong>
                    <small><?php echo e(\Carbon\Carbon::parse($bl->ThoiGianNopBai)->format('d/m/Y H:i')); ?></small>
                </div>
                <div style="display:flex; align-items:center; gap: 10px;">
                    <div class="score-display <?php echo e($bl->TongDiem >= 5 ? 'pass' : 'fail'); ?>" style="font-size:18px;">
                        <?php echo e(number_format($bl->TongDiem, 2)); ?>

                    </div>
                    <a href="<?php echo e(route('student.results.show', $bl->MaBaiLam)); ?>" class="btn-outline">
                        Xem
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="empty">Chưa có bài làm nào.</p>
            <?php endif; ?>
            <a href="<?php echo e(route('student.results.index')); ?>" class="view-all">Xem tất cả →</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/student/dashboard.blade.php ENDPATH**/ ?>