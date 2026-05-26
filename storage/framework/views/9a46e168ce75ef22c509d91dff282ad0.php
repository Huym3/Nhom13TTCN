

<?php $__env->startSection('title', 'Quản lý Đề thi'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h4 class="fw-bold text-primary mb-0"><i class="bi bi-journal-check"></i> QUẢN LÝ ĐỀ THI</h4>
        <a href="/giao-vien/ghep-de" class="btn btn-primary fw-bold">
            <i class="bi bi-plus-circle me-1"></i> Tạo đề thi mới
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Mã đề</th>
                        <th style="width: 30%;">Tên đề thi</th>
                        <th>Cấu trúc</th>
                        <th>Trạng thái</th>
                        <th>Lượt thi</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4 fw-bold">101</td>
                        <td class="fw-bold text-dark">Đề thi thử THPT Quốc gia 2025 - Lần 1</td>
                        <td class="small text-muted">90 phút | 50 câu</td>
                        <td><span class="badge bg-success">Đã xuất bản</span></td>
                        <td><span class="fw-bold">128</span> học sinh</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-info" title="Xem thống kê điểm"><i class="bi bi-bar-chart"></i></button>
                            <button class="btn btn-sm btn-outline-secondary" title="Sửa (Khóa nếu đã có ng thi)" disabled><i class="bi bi-pencil-square"></i></button>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="ps-4 fw-bold">102</td>
                        <td class="fw-bold text-dark">Đề ôn tập giữa kì 2 - Lớp 12A1</td>
                        <td class="small text-muted">45 phút | 25 câu</td>
                        <td><span class="badge bg-warning text-dark">Đang nháp (Draft)</span></td>
                        <td><span class="fw-bold">0</span> học sinh</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-success" title="Xuất bản ngay"><i class="bi bi-send-check"></i></button>
                            <button class="btn btn-sm btn-outline-primary" title="Sửa"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-sm btn-outline-danger" title="Xóa"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\TTCN\Nhom13TTCN\resources\views/teacher/exams.blade.php ENDPATH**/ ?>