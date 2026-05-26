<?php $__env->startSection('title', 'Lịch sử bài làm'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <div class="page-title">Lịch sử bài làm</div>
        <div class="page-sub">Tổng hợp tất cả các bài thi bạn đã hoàn thành</div>
    </div>
</div>

<div class="results-list">
    <?php $__empty_1 = true; $__currentLoopData = $baiLam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="result-card glass">
        <div class="result-info">
            <h3><?php echo e($bl->TenDeThi); ?></h3>
            <div class="result-meta">
                <span><?php echo e(\Carbon\Carbon::parse($bl->ThoiGianNopBai)->format('d/m/Y H:i')); ?></span>
                <span><?php echo e(gmdate('i:s', $bl->TongThoiGianLamBai)); ?></span>
                <span style="color: var(--green);"><?php echo e($bl->SoCauDung); ?> đúng</span>
                <span style="color: var(--red);"><?php echo e($bl->SoCauSai); ?> sai</span>
            </div>
            <div class="score-breakdown">
                <span>P.I: <strong style="color: var(--text-primary);"><?php echo e(number_format($bl->DiemPhan1, 2)); ?>đ</strong></span>
                <span>P.II: <strong style="color: var(--text-primary);"><?php echo e(number_format($bl->DiemPhan2, 2)); ?>đ</strong></span>
                <span>P.III: <strong style="color: var(--text-primary);"><?php echo e(number_format($bl->DiemPhan3, 2)); ?>đ</strong></span>
            </div>
        </div>
        <div class="result-right">
            <div class="score-display <?php echo e($bl->TongDiem >= 5 ? 'score-pass' : 'score-fail'); ?>">
                <?php echo e(number_format($bl->TongDiem, 2)); ?>

            </div>
            <a href="<?php echo e(route('student.results.show', $bl->MaBaiLam)); ?>" class="btn-outline">
                Xem chi tiết
            </a>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="empty-state glass" style="padding: 48px;">
        <div class="empty-icon">📋</div>
        <p>Bạn chưa làm bài thi nào.</p>
        <a href="<?php echo e(route('student.exams')); ?>" class="btn-primary">Làm bài ngay</a>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/student/results/index.blade.php ENDPATH**/ ?>