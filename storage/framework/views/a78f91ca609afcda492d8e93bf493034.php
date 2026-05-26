

<?php $__env->startSection('title', 'Lịch sử làm bài'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h3 class="fw-bold mb-4"><i class="bi bi-clock-history"></i> LỊCH SỬ LÀM BÀI CỦA BẠN</h3>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tên đề thi</th>
                        <th>Ngày làm</th>
                        <th>Thời gian</th>
                        <th>Điểm số</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">Đề minh họa BGD năm 2025</div>
                            <span class="text-muted small">Môn: Toán</span>
                        </td>
                        <td>09/05/2026</td>
                        <td>45:12</td>
                        <td><span class="badge bg-success fs-6">8.60</span></td>
                        <td class="text-center">
                            <a href="/ket-qua" class="btn btn-sm btn-outline-primary px-3">Xem chi tiết</a>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\TTCN\Nhom13TTCN\resources\views/student-history.blade.php ENDPATH**/ ?>