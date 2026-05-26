<?php $__env->startSection('title', 'Danh sách đề thi'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <div class="page-title">Danh sách đề thi</div>
        <div class="page-sub">Chọn đề thi và bắt đầu luyện tập</div>
    </div>
</div>

<div class="exam-list">
    <?php $__empty_1 = true; $__currentLoopData = $deThi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $de): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="exam-card glass">
        <div class="exam-info">
            <h3><?php echo e($de->TenDeThi); ?></h3>
            <div class="exam-meta">
                <span><?php echo e($de->ThoiGian); ?> phút</span>
                <span><?php echo e($de->SoCauHoi); ?> câu</span>
                <?php if($de->MaDe ?? null): ?>
                <span>Mã: <?php echo e($de->MaDe); ?></span>
                <?php endif; ?>
                <span><?php echo e(\Carbon\Carbon::parse($de->NgayTao)->format('d/m/Y')); ?></span>
            </div>
        </div>
        <a href="<?php echo e(route('student.exams.start', $de->MaDeThi)); ?>"
           class="btn-primary"
           onclick="return confirm('Bạn có chắc muốn bắt đầu làm bài? Thời gian sẽ tính ngay!')">
            Bắt đầu làm bài
        </a>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="empty-state glass" style="padding: 48px;">
        <div class="empty-icon">📄</div>
        <p>Chưa có đề thi nào được đăng.</p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/student/exams/index.blade.php ENDPATH**/ ?>