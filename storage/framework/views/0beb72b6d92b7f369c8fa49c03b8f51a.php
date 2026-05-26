<?php $__env->startSection('title', 'Lịch sử bài làm'); ?>

<?php $__env->startSection('content'); ?>
<div class="container pb-5">
    
    <h3 class="fw-bold mb-4"><i class="bi bi-person-lines-fill text-primary"></i> LỊCH SỬ BÀI LÀM CỦA TÔI</h3>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Tên đề thi</th>
                            <th>Thời gian nộp bài</th>
                            <th>Thời gian làm</th>
                            <th>Điểm số</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">1</td>
                            <td>
                                <div class="fw-bold text-dark">Đề thi thử THPT Quốc gia 2025 - Cụm Nam Định</div>
                                <span class="badge bg-light text-dark border">Mã đề: 101</span>
                            </td>
                            <td class="text-muted">14:30 - 08/05/2026</td>
                            <td>85 phút</td>
                            <td><span class="badge bg-success fs-6">8.60</span></td>
                            <td class="text-center">
                                <a href="/ket-qua" class="btn btn-sm btn-primary px-3">Xem đáp án & Bài làm</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">2</td>
                            <td>
                                <div class="fw-bold text-dark">Kiểm tra giữa kì 2 - Lớp 12</div>
                                <span class="badge bg-light text-dark border">Mã đề: 204</span>
                            </td>
                            <td class="text-muted">09:15 - 05/05/2026</td>
                            <td>45 phút</td>
                            <td><span class="badge bg-warning text-dark fs-6">6.25</span></td>
                            <td class="text-center">
                                <a href="/ket-qua" class="btn btn-sm btn-primary px-3">Xem đáp án & Bài làm</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\TTCN\Nhom13TTCN\resources\views/student/dashboard.blade.php ENDPATH**/ ?>