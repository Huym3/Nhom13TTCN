<?php $__env->startSection('title', 'Tạo đề thi mới'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-header-left">
        <h2>➕ Tạo đề thi mới</h2>
        <p>Điền thông tin cơ bản — câu hỏi thêm sau ở bước tiếp theo</p>
    </div>
    <a href="<?php echo e(route('teacher.exams.index')); ?>" class="btn-outline">← Quay lại</a>
</div>

<?php if($errors->any()): ?>
    <div class="alert alert-error">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <div>• <?php echo e($e); ?></div> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<div class="form-card glass">
    <form method="POST" action="<?php echo e(route('teacher.exams.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="tenDeThi">Tên đề thi <span class="required">*</span></label>
            <input type="text" id="tenDeThi" name="tenDeThi"
                   value="<?php echo e(old('tenDeThi')); ?>"
                   placeholder="VD: Đề thi thử THPT lần 1 - Toán 2026"
                   class="form-control">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="maDe">Mã đề</label>
                <input type="text" id="maDe" name="maDe"
                       value="<?php echo e(old('maDe')); ?>"
                       placeholder="VD: 0018"
                       class="form-control">
                <small class="form-hint">Để trống nếu chưa có mã đề cụ thể</small>
            </div>

            <div class="form-group">
                <label for="thoiGian">Thời gian làm bài <span class="required">*</span></label>
                <select id="thoiGian" name="thoiGian" class="form-control">
                    <option value="">-- Chọn thời gian --</option>
                    <?php $__currentLoopData = [15 => '15 phút', 45 => '45 phút', 60 => '60 phút', 90 => '90 phút']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php echo e(old('thoiGian') == $val ? 'selected' : ''); ?>>
                            <?php echo e($label); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="cauTruc">Mô tả cấu trúc đề</label>
            <textarea id="cauTruc" name="cauTruc" rows="3"
                      class="form-control"
                      placeholder="VD: Phần I (12 câu TN × 0.25đ) + Phần II (4 câu DS × 1đ) + Phần III (6 câu TLS × 0.5đ)"><?php echo e(old('cauTruc')); ?></textarea>
        </div>

        <div class="form-actions">
            <a href="<?php echo e(route('teacher.exams.index')); ?>" class="btn-outline">Hủy</a>
            <button type="submit" class="btn-primary">Tạo đề thi →</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/teacher/exams/create.blade.php ENDPATH**/ ?>