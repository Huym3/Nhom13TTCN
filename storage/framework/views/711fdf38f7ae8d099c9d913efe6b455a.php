<?php $__env->startSection('title', 'Sửa đề thi'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-header-left">
        <h2>✏️ <?php echo e($exam->TenDeThi); ?></h2>
        <p>Chỉnh sửa thông tin và thêm câu hỏi vào đề</p>
    </div>
    <a href="<?php echo e(route('teacher.exams.index')); ?>" class="btn-outline">← Quay lại</a>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-error"><?php echo e(session('error')); ?></div>
<?php endif; ?>


<div class="form-card glass" style="margin-bottom:24px">
    <h3 class="section-title">Thông tin đề thi</h3>
    <form method="POST" action="<?php echo e(route('teacher.exams.update', $exam->MaDeThi)); ?>">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        <div class="form-row">
            <div class="form-group" style="flex:2">
                <label>Tên đề thi</label>
                <input type="text" name="tenDeThi" value="<?php echo e($exam->TenDeThi); ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Mã đề</label>
                <input type="text" name="maDe" value="<?php echo e($exam->MaDe); ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Thời gian</label>
                <select name="thoiGian" class="form-control">
                    <?php $__currentLoopData = [15,45,60,90]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e($exam->ThoiGian == $t ? 'selected' : ''); ?>><?php echo e($t); ?> phút</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Mô tả cấu trúc</label>
            <textarea name="cauTruc" rows="2" class="form-control"><?php echo e($exam->CauTrucDe); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">💾 Lưu thay đổi</button>
        </div>
    </form>
</div>


<div class="form-card glass" style="margin-bottom:24px">
    <h3 class="section-title">
        Câu hỏi trong đề
        <span class="badge-count"><?php echo e($cauHoiTrongDe->count()); ?> câu</span>
    </h3>

    <?php
        $tongDiem = $cauHoiTrongDe->sum('DiemCauHoi');
        $phanI    = $cauHoiTrongDe->where('Phan','I');
        $phanII   = $cauHoiTrongDe->where('Phan','II');
        $phanIII  = $cauHoiTrongDe->where('Phan','III');
    ?>

    
    <div class="diem-summary">
        <div class="diem-item">
            <span class="diem-label">Phần I (TN)</span>
            <span class="diem-val"><?php echo e($phanI->count()); ?> câu × 0.25 = <?php echo e($phanI->count() * 0.25); ?>đ</span>
        </div>
        <div class="diem-item">
            <span class="diem-label">Phần II (Đúng/Sai)</span>
            <span class="diem-val"><?php echo e($phanII->count()); ?> câu × 1.00 = <?php echo e($phanII->count() * 1.0); ?>đ</span>
        </div>
        <div class="diem-item">
            <span class="diem-label">Phần III (TLS)</span>
            <span class="diem-val"><?php echo e($phanIII->count()); ?> câu × 0.50 = <?php echo e($phanIII->count() * 0.5); ?>đ</span>
        </div>
        <div class="diem-item diem-tong">
            <span class="diem-label">Tổng điểm</span>
            <span class="diem-val"><?php echo e(number_format($tongDiem, 2)); ?> / 10.00đ</span>
        </div>
    </div>

    <?php if($cauHoiTrongDe->isEmpty()): ?>
        <div class="empty" style="padding:20px;text-align:center;color:var(--text-muted)">
            Chưa có câu hỏi nào trong đề. Thêm câu hỏi từ danh sách bên dưới.
        </div>
    <?php else: ?>
        <?php $__currentLoopData = ['I' => 'Phần I — Trắc nghiệm nhiều lựa chọn', 'II' => 'Phần II — Đúng/Sai', 'III' => 'Phần III — Trả lời ngắn số']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phan => $tenPhan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $cauHoiPhan = $cauHoiTrongDe->where('Phan', $phan); ?>
            <?php if($cauHoiPhan->isNotEmpty()): ?>
                <div class="phan-header"><?php echo e($tenPhan); ?></div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="50">STT</th>
                            <th>Nội dung câu hỏi</th>
                            <th width="100">Chuyên đề</th>
                            <th width="80">Độ khó</th>
                            <th width="60">Điểm</th>
                            <th width="80">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $cauHoiPhan->sortBy('ThuTu'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center"><?php echo e($cq->ThuTu); ?></td>
                            <td><?php echo e(Str::limit($cq->NoiDungCH, 80)); ?></td>
                            <td><?php echo e($cq->TenChuyenDe); ?></td>
                            <td>
                                <span class="badge-dokho badge-<?php echo e(strtolower(str_replace(' ','',$cq->DoKho))); ?>">
                                    <?php echo e($cq->DoKho); ?>

                                </span>
                            </td>
                            <td class="text-center"><?php echo e(number_format($cq->DiemCauHoi, 2)); ?></td>
                            <td class="text-center">
                                <form action="<?php echo e(route('teacher.exams.removeQuestion', $exam->MaDeThi)); ?>"
                                      method="POST" style="display:inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <input type="hidden" name="maCauHoi" value="<?php echo e($cq->MaCauHoi); ?>">
                                    <button type="submit" class="btn-icon btn-danger-sm"
                                        title="Xóa khỏi đề"
                                        onclick="return confirm('Xóa câu hỏi này khỏi đề?')">🗑</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</div>


<div class="form-card glass">
    <h3 class="section-title">
        Thêm câu hỏi vào đề
        <span class="badge-count"><?php echo e($cauHoiCoThe->count()); ?> câu chưa thêm</span>
    </h3>

    
    <div class="filter-bar" style="margin-bottom:16px;display:flex;gap:12px;flex-wrap:wrap">
        <select id="filterLoai" class="form-control" style="width:160px">
            <option value="">Tất cả loại</option>
            <option value="TN">Trắc nghiệm (TN)</option>
            <option value="DS">Đúng/Sai (DS)</option>
            <option value="TLS">Trả lời số (TLS)</option>
        </select>
        <input type="text" id="filterText" class="form-control" style="width:260px"
               placeholder="🔍 Tìm kiếm nội dung câu hỏi...">
    </div>

    <?php if($cauHoiCoThe->isEmpty()): ?>
        <div class="empty" style="padding:20px;text-align:center;color:var(--text-muted)">
            Tất cả câu hỏi của bạn đã được thêm vào đề, hoặc bạn chưa tạo câu hỏi nào.
        </div>
    <?php else: ?>
        <table class="data-table" id="tableCoThe">
            <thead>
                <tr>
                    <th>Nội dung câu hỏi</th>
                    <th width="100">Chuyên đề</th>
                    <th width="70">Loại</th>
                    <th width="80">Độ khó</th>
                    <th width="130">Thêm vào phần</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $cauHoiCoThe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr data-loai="<?php echo e($q->LoaiCauHoi); ?>" data-noidung="<?php echo e(strtolower($q->NoiDungCH)); ?>">
                    <td><?php echo e(Str::limit($q->NoiDungCH, 90)); ?></td>
                    <td><?php echo e($q->TenChuyenDe); ?></td>
                    <td>
                        <span class="badge-loai badge-loai-<?php echo e(strtolower($q->LoaiCauHoi)); ?>">
                            <?php echo e($q->LoaiCauHoi); ?>

                        </span>
                    </td>
                    <td>
                        <span class="badge-dokho badge-<?php echo e(strtolower(str_replace(' ','',$q->DoKho))); ?>">
                            <?php echo e($q->DoKho); ?>

                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;align-items:center">
                        <select class="form-control phan-select" style="width:70px;padding:4px 6px;font-size:13px">
                            <?php if($q->LoaiCauHoi === 'TN'): ?>  <option value="I">I</option> <?php endif; ?>
                            <?php if($q->LoaiCauHoi === 'DS'): ?>  <option value="II">II</option> <?php endif; ?>
                            <?php if($q->LoaiCauHoi === 'TLS'): ?> <option value="III">III</option> <?php endif; ?>
                        </select>
                        <button class="btn-primary btn-sm btn-them-cau"
                                data-id="<?php echo e($q->MaCauHoi); ?>"
                                data-url="<?php echo e(route('teacher.exams.addQuestion', $exam->MaDeThi)); ?>">
                            + Thêm
                        </button>
                    </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script>
// Filter câu hỏi chưa thêm
const filterLoai = document.getElementById('filterLoai');
const filterText = document.getElementById('filterText');

function applyFilter() {
    const loai = filterLoai?.value.toLowerCase() ?? '';
    const text = filterText?.value.toLowerCase() ?? '';
    document.querySelectorAll('#tableCoThe tbody tr').forEach(row => {
        const matchLoai = !loai || row.dataset.loai?.toLowerCase() === loai;
        const matchText = !text || row.dataset.noidung?.includes(text);
        row.style.display = matchLoai && matchText ? '' : 'none';
    });
}

filterLoai?.addEventListener('change', applyFilter);
filterText?.addEventListener('input', applyFilter);
</script>

<script>
document.querySelectorAll('.btn-them-cau').forEach(btn => {
    btn.addEventListener('click', function() {
        const maCauHoi = this.dataset.id;
        const url = this.dataset.url;
        const phan = this.previousElementSibling.value;
        const row = this.closest('tr');
        const btnEl = this;

        btnEl.disabled = true;
        btnEl.textContent = '...';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content
                    || '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ maCauHoi, phan })
        })
        .then(res => {
            if (res.ok || res.redirected) {
                // Ẩn dòng câu hỏi vừa thêm
                row.style.opacity = '0.4';
                row.style.pointerEvents = 'none';
                btnEl.textContent = '✓ Đã thêm';
                btnEl.style.background = 'var(--green)'; // Đã sửa màu chuẩn theo glass.css

                // Cập nhật số câu chưa thêm
                const badge = document.querySelector('.badge-count:last-of-type');
                if (badge) {
                    const cur = parseInt(badge.textContent);
                    badge.textContent = (cur - 1) + ' câu chưa thêm';
                }
            } else {
                btnEl.disabled = false;
                btnEl.textContent = '+ Thêm';
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        })
        .catch(() => {
            btnEl.disabled = false;
            btnEl.textContent = '+ Thêm';
            alert('Có lỗi xảy ra, vui lòng thử lại.');
        });
    });
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\thuctap\resources\views/teacher/exams/edit.blade.php ENDPATH**/ ?>