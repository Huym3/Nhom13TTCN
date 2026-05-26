<?php $__env->startSection('title', 'Ngân hàng câu hỏi'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Ngân hàng câu hỏi</div>
        <div class="page-sub">Quản lý toàn bộ câu hỏi bạn đã tạo</div>
    </div>
    <a href="<?php echo e(route('teacher.questions.create')); ?>" class="btn-primary">+ Tạo câu hỏi mới</a>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-error"><?php echo e(session('error')); ?></div>
<?php endif; ?>


<div class="glass" style="padding: 14px 18px; margin-bottom: 16px;">
    <div class="filter-bar">
        <select id="filterLoai" class="form-control" style="width: 180px;">
            <option value="">Tất cả loại</option>
            <option value="TN">Trắc nghiệm (TN)</option>
            <option value="DS">Đúng/Sai (DS)</option>
            <option value="TLS">Trả lời số (TLS)</option>
        </select>
        <select id="filterDoKho" class="form-control" style="width: 160px;">
            <option value="">Tất cả độ khó</option>
            <option value="De">Dễ</option>
            <option value="Trung Binh">Trung Bình</option>
            <option value="Nang Cao">Nâng Cao</option>
        </select>
        <input type="text" id="filterText" class="form-control" style="width: 260px;"
               placeholder="Tìm nội dung câu hỏi...">
        <span id="countResult" style="font-size: 12px; color: var(--text-muted);"></span>
    </div>
</div>

<?php if($questions->isEmpty()): ?>
    <div class="empty-state glass" style="padding: 48px;">
        <div class="empty-icon">📝</div>
        <p>Bạn chưa có câu hỏi nào.</p>
        <a href="<?php echo e(route('teacher.questions.create')); ?>" class="btn-primary">Tạo câu hỏi đầu tiên</a>
    </div>
<?php else: ?>
    <div class="glass" style="overflow: hidden;">
        <table class="data-table" id="tableQuestions">
            <thead>
                <tr>
                    <th width="44">#</th>
                    <th>Nội dung câu hỏi</th>
                    <th width="130">Chuyên đề</th>
                    <th width="80">Loại</th>
                    <th width="100">Độ khó</th>
                    <th width="100">Ngày tạo</th>
                    <th width="110">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr data-loai="<?php echo e($q->LoaiCauHoi); ?>"
                    data-dokho="<?php echo e($q->DoKho); ?>"
                    data-noidung="<?php echo e(strtolower($q->NoiDungCH)); ?>">
                    <td class="text-center" style="color: var(--text-muted); font-size: 12px;"><?php echo e($i + 1); ?></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <?php if($q->HinhAnh): ?>
                                <img src="<?php echo e(asset('storage/' . $q->HinhAnh)); ?>"
                                     style="width: 56px; height: 38px; object-fit: cover; border-radius: 6px; border: 1px solid var(--glass-border-strong); flex-shrink: 0;">
                            <?php endif; ?>
                            <span style="color: var(--text-primary); font-weight: 500;"><?php echo e(Str::limit($q->NoiDungCH, 55)); ?></span>
                        </div>
                    </td>
                    <td style="font-size: 12px; color: var(--text-muted);"><?php echo e($q->TenChuyenDe); ?></td>
                    <td>
                        <span class="badge-loai badge-loai-<?php echo e(strtolower($q->LoaiCauHoi)); ?>">
                            <?php echo e($q->LoaiCauHoi); ?>

                        </span>
                    </td>
                    <td>
                        <span class="badge-dokho badge-<?php echo e(strtolower(str_replace(' ', '', $q->DoKho))); ?>">
                            <?php echo e($q->DoKho); ?>

                        </span>
                    </td>
                    <td class="text-center" style="font-size: 12px; color: var(--text-muted);">
                        <?php echo e(\Carbon\Carbon::parse($q->NgayTao)->format('d/m/Y')); ?>

                    </td>
                    <td class="text-center">
                        <div style="display: flex; gap: 6px; justify-content: center;">
                            <a href="<?php echo e(route('teacher.questions.edit', $q->MaCauHoi)); ?>"
                               class="btn-outline btn-sm">✏️</a>
                            <form action="<?php echo e(route('teacher.questions.destroy', $q->MaCauHoi)); ?>"
                                  method="POST" style="display: inline;">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-danger-sm"
                                    onclick="return confirm('Xóa câu hỏi này?')">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<script>
const filterLoai  = document.getElementById('filterLoai');
const filterDoKho = document.getElementById('filterDoKho');
const filterText  = document.getElementById('filterText');
const countResult = document.getElementById('countResult');

function applyFilter() {
    const loai  = filterLoai?.value  ?? '';
    const dokho = filterDoKho?.value ?? '';
    const text  = filterText?.value.toLowerCase() ?? '';
    let visible = 0;

    document.querySelectorAll('#tableQuestions tbody tr').forEach(row => {
        const matchLoai  = !loai  || row.dataset.loai  === loai;
        const matchDoKho = !dokho || row.dataset.dokho === dokho;
        const matchText  = !text  || row.dataset.noidung.includes(text);
        const show = matchLoai && matchDoKho && matchText;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    countResult.textContent = `Hiển thị ${visible} câu hỏi`;
}

filterLoai?.addEventListener('change', applyFilter);
filterDoKho?.addEventListener('change', applyFilter);
filterText?.addEventListener('input', applyFilter);
applyFilter();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/teacher/questions/index.blade.php ENDPATH**/ ?>