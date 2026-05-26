<?php $__env->startSection('title', 'Sửa câu hỏi'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-header-left">
        <h2>✏️ Sửa câu hỏi</h2>
        <p>Chỉnh sửa nội dung câu hỏi — loại câu hỏi không thể thay đổi</p>
    </div>
    <a href="<?php echo e(route('teacher.questions.index')); ?>" class="btn-outline">← Quay lại</a>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if($errors->any()): ?>
    <div class="alert alert-error">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div>• <?php echo e($e); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<div class="form-card glass">
    <form method="POST" action="<?php echo e(route('teacher.questions.update', $question->MaCauHoi)); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        <h3 class="section-title">Thông tin câu hỏi</h3>

        <div class="form-row">
            <div class="form-group" style="flex:2">
                <label>Chuyên đề</label>
                <select name="maChuyenDe" class="form-control">
                    <?php $__currentLoopData = $chuyenDe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cd->MaChuyenDe); ?>"
                            <?php echo e($question->MaChuyenDe == $cd->MaChuyenDe ? 'selected' : ''); ?>>
                            <?php echo e($cd->TenChuyenDe); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label>Độ khó</label>
                <select name="doKho" class="form-control">
                    <option value="De"         <?php echo e($question->DoKho === 'De'         ? 'selected' : ''); ?>>Dễ</option>
                    <option value="Trung Binh" <?php echo e($question->DoKho === 'Trung Binh' ? 'selected' : ''); ?>>Trung Bình</option>
                    <option value="Nang Cao"   <?php echo e($question->DoKho === 'Nang Cao'   ? 'selected' : ''); ?>>Nâng Cao</option>
                </select>
            </div>
            <div class="form-group">
                <label>Loại câu hỏi</label>
                <input type="text" class="form-control"
                       value="<?php echo e($question->LoaiCauHoi); ?>" disabled
                       style="background:rgba(241,245,249,0.5);cursor:not-allowed">
                <small class="form-hint">Không thể thay đổi loại câu hỏi</small>
            </div>
        </div>

        <div class="form-group">
            <label>Nội dung câu hỏi</label>
            <textarea name="noiDung" rows="4" class="form-control"><?php echo e(old('noiDung', $question->NoiDungCH)); ?></textarea>
        </div>

        <div class="form-group">
            <label>Hình ảnh câu hỏi</label>
            <?php if($question->HinhAnh): ?>
                <div style="margin: 10px 0; border: 1px solid var(--glass-border-strong); padding: 5px; width: fit-content; border-radius: 8px; background: rgba(255,255,255,0.5);">
                    <img src="<?php echo e(asset('storage/' . $question->HinhAnh)); ?>" style="max-width: 250px; display: block;">
                </div>
            <?php endif; ?>
            <input type="file" name="anhCauHoi" class="form-control" accept="image/*">
            <small class="form-hint">Tải ảnh mới lên nếu muốn thay đổi ảnh cũ.</small>
        </div>

        <div class="form-group">
            <label>Giải thích đáp án</label>
            <textarea name="giaiThich" rows="3" class="form-control"><?php echo e(old('giaiThich', $question->GiaiThich)); ?></textarea>
        </div>

        <?php if($question->LoaiCauHoi === 'TN'): ?>
        <h3 class="section-title">Đáp án — Trắc nghiệm</h3>
        <div class="form-group" style="max-width: 250px;">
            <label>Chọn đáp án đúng <span class="required">*</span></label>
            <select name="dapAnDung" class="form-control">
                <?php $__currentLoopData = $dapAnTN; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $da): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($da->KyHieu); ?>" <?php echo e($da->LaDapAnDung ? 'selected' : ''); ?>>
                        Đáp án <?php echo e($da->KyHieu); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <?php endif; ?>

        <?php if($question->LoaiCauHoi === 'DS'): ?>
        <h3 class="section-title">Chỉnh sửa đáp án Đúng/Sai</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="100">Ký hiệu</th>
                    <th>Trạng thái đáp án</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $cacY; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-center font-bold" style="font-size: 1.2rem;"><?php echo e(strtoupper($y->KyHieu)); ?></td>
                    <td>
                        <select name="dapAnY_<?php echo e($y->KyHieu); ?>" class="form-control">
                            <option value="1" <?php echo e($y->DapAnDung == 1 ? 'selected' : ''); ?>>✅ Đúng</option>
                            <option value="0" <?php echo e($y->DapAnDung == 0 ? 'selected' : ''); ?>>❌ Sai</option>
                        </select>
                        <input type="hidden" name="noiDungY_<?php echo e($y->KyHieu); ?>" value="<?php echo e($y->NoiDungY); ?>">
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>

        <?php if($question->LoaiCauHoi === 'TLS' && $dapAnSo): ?>
        <h3 class="section-title">Đáp án — Trả lời số</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Đáp án số</label>
                <input type="number" step="any" name="dapAnSo" class="form-control"
                       value="<?php echo e(old('dapAnSo', $dapAnSo->DapAnSo)); ?>">
            </div>
            <div class="form-group">
                <label>Sai số chấp nhận</label>
                <input type="number" step="any" name="saiSo" class="form-control"
                       value="<?php echo e(old('saiSo', $dapAnSo->SaiSoChapNhan)); ?>">
            </div>
        </div>
        <?php endif; ?>

        <div class="form-actions">
            <a href="<?php echo e(route('teacher.questions.index')); ?>" class="btn-outline">Hủy</a>
            <button type="submit" class="btn-primary">💾 Lưu thay đổi</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/teacher/questions/edit.blade.php ENDPATH**/ ?>