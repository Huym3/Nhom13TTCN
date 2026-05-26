<?php $__env->startSection('title', 'Tạo câu hỏi mới'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Tạo câu hỏi mới</div>
        <div class="page-sub">Chọn loại câu hỏi để hiển thị form nhập đáp án phù hợp</div>
    </div>
    <a href="<?php echo e(route('teacher.questions.index')); ?>" class="btn-outline">← Quay lại</a>
</div>

<?php if($errors->any()): ?>
    <div class="alert alert-error">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div>• <?php echo e($e); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<div class="form-card glass">
    <form method="POST" action="<?php echo e(route('teacher.questions.store')); ?>" id="formCauHoi" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <h3 class="section-title">Thông tin câu hỏi</h3>

        <div class="form-row">
            <div class="form-group" style="flex: 2;">
                <label>Chuyên đề <span class="required">*</span></label>
                <select name="maChuyenDe" class="form-control">
                    <option value="">-- Chọn chuyên đề --</option>
                    <?php $__currentLoopData = $chuyenDe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cd->MaChuyenDe); ?>"
                            <?php echo e(old('maChuyenDe') == $cd->MaChuyenDe ? 'selected' : ''); ?>>
                            <?php echo e($cd->TenChuyenDe); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label>Độ khó <span class="required">*</span></label>
                <select name="doKho" class="form-control">
                    <option value="">-- Chọn --</option>
                    <option value="De"         <?php echo e(old('doKho') === 'De'         ? 'selected' : ''); ?>>Dễ</option>
                    <option value="Trung Binh" <?php echo e(old('doKho') === 'Trung Binh' ? 'selected' : ''); ?>>Trung Bình</option>
                    <option value="Nang Cao"   <?php echo e(old('doKho') === 'Nang Cao'   ? 'selected' : ''); ?>>Nâng Cao</option>
                </select>
            </div>
            <div class="form-group">
                <label>Loại câu hỏi <span class="required">*</span></label>
                <select name="loaiCauHoi" id="loaiCauHoi" class="form-control">
                    <option value="">-- Chọn loại --</option>
                    <option value="TN"  <?php echo e(old('loaiCauHoi') === 'TN'  ? 'selected' : ''); ?>>Trắc nghiệm (TN)</option>
                    <option value="DS"  <?php echo e(old('loaiCauHoi') === 'DS'  ? 'selected' : ''); ?>>Đúng/Sai (DS)</option>
                    <option value="TLS" <?php echo e(old('loaiCauHoi') === 'TLS' ? 'selected' : ''); ?>>Trả lời số (TLS)</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Nội dung câu hỏi <span class="required">*</span></label>
            <textarea name="noiDung" rows="4" class="form-control"
                      placeholder="Nhập nội dung câu hỏi..."><?php echo e(old('noiDung')); ?></textarea>
        </div>

        <div class="form-group">
            <label>Ảnh câu hỏi (Chứa đề bài và đáp án)</label>
            <input type="file" name="anhCauHoi" class="form-control" accept="image/*">
            <span class="form-hint">Hệ thống sẽ ưu tiên hiển thị ảnh này thay cho nội dung chữ.</span>
        </div>

        <div class="form-group">
            <label>Giải thích đáp án</label>
            <textarea name="giaiThich" rows="3" class="form-control"
                      placeholder="Giải thích tại sao đáp án đúng (học sinh xem sau khi nộp bài)"><?php echo e(old('giaiThich')); ?></textarea>
        </div>

        
        <div id="block-TN" class="dap-an-block" style="display: none;">
            <h3 class="section-title">Đáp án — Trắc nghiệm</h3>
            <div class="form-group" style="max-width: 200px;">
                <label>Đáp án đúng <span class="required">*</span></label>
                <select name="dapAnDung" class="form-control">
                    <option value="">-- Chọn --</option>
                    <?php $__currentLoopData = ['A','B','C','D']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k); ?>" <?php echo e(old('dapAnDung') === $k ? 'selected' : ''); ?>>
                            Đáp án <?php echo e($k); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        
        <div id="block-DS" class="dap-an-block" style="display: none;">
            <h3 class="section-title">Đáp án — Đúng/Sai (chọn trạng thái cho 4 ý)</h3>
            <div class="ds-grid">
                <?php $__currentLoopData = ['a','b','c','d']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="ds-item">
                    <div class="ds-label"><?php echo e(strtoupper($k)); ?></div>
                    <div class="form-group" style="margin-bottom: 0; flex: 1;">
                        <select name="dapAnY_<?php echo e($k); ?>" class="form-control">
                            <option value="1">✅ Đúng</option>
                            <option value="0">❌ Sai</option>
                        </select>
                    </div>
                    <input type="hidden" name="noiDungY_<?php echo e($k); ?>" value="Nội dung trong ảnh">
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div id="block-TLS" class="dap-an-block" style="display: none;">
            <h3 class="section-title">Đáp án — Trả lời số</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Đáp án số <span class="required">*</span></label>
                    <input type="number" name="dapAnSo" step="any" class="form-control"
                           value="<?php echo e(old('dapAnSo')); ?>" placeholder="VD: 3.14">
                </div>
                <div class="form-group">
                    <label>Sai số chấp nhận</label>
                    <input type="number" name="saiSo" step="any" class="form-control"
                           value="<?php echo e(old('saiSo', '0.005')); ?>" placeholder="0.005">
                    <span class="form-hint">Câu trả lời trong khoảng ± sai số sẽ được tính đúng</span>
                </div>
                <div class="form-group" style="flex: 2;">
                    <label>Ghi chú đơn vị</label>
                    <input type="text" name="ghiChu" class="form-control"
                           value="<?php echo e(old('ghiChu')); ?>" placeholder="VD: triệu đồng, km/h...">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="<?php echo e(route('teacher.questions.index')); ?>" class="btn-outline">Hủy</a>
            <button type="submit" class="btn-primary">💾 Lưu câu hỏi</button>
        </div>
    </form>
</div>

<script>
const loaiSelect = document.getElementById('loaiCauHoi');
const blocks     = document.querySelectorAll('.dap-an-block');

function showBlock(loai) {
    blocks.forEach(b => b.style.display = 'none');
    if (loai) {
        const target = document.getElementById('block-' + loai);
        if (target) target.style.display = 'block';
    }
}

loaiSelect.addEventListener('change', () => showBlock(loaiSelect.value));
showBlock('<?php echo e(old("loaiCauHoi")); ?>');
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/teacher/questions/create.blade.php ENDPATH**/ ?>