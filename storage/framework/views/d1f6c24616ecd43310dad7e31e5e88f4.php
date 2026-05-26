

<?php $__env->startSection('title', 'Admin - Tổng quan Hệ thống'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 pb-5">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <h3 class="fw-bold text-dark mb-0"><i class="bi bi-shield-lock-fill text-danger"></i> TRUNG TÂM QUẢN TRỊ (ADMIN)</h3>
        <span class="text-muted">Cập nhật lúc: <?php echo e(date('d/m/Y H:i')); ?></span>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-6 fw-bold text-uppercase mb-1">Tổng Học Sinh</div>
                            <div class="fs-2 fw-bold">1,250</div>
                        </div>
                        <i class="bi bi-mortarboard fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-6 fw-bold text-uppercase mb-1">Giáo Viên</div>
                            <div class="fs-2 fw-bold">15</div>
                        </div>
                        <i class="bi bi-person-workspace fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-6 fw-bold text-uppercase mb-1">Đề Thi Hệ Thống</div>
                            <div class="fs-2 fw-bold">120</div>
                        </div>
                        <i class="bi bi-journal-album fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-6 fw-bold text-uppercase mb-1">Tài khoản bị khóa</div>
                            <div class="fs-2 fw-bold">8</div>
                        </div>
                        <i class="bi bi-person-x fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="fw-bold mb-0">Công cụ Quản trị viên</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="/admin/quan-ly-tai-khoan" class="btn btn-outline-primary text-start fw-bold py-3">
                            <i class="bi bi-people me-2"></i> Quản lý Người dùng (Cấp quyền)
                        </a>
                        <a href="#" class="btn btn-outline-secondary text-start fw-bold py-3">
                            <i class="bi bi-database-gear me-2"></i> Sao lưu Dữ liệu (Backup)
                        </a>
                        <a href="#" class="btn btn-outline-danger text-start fw-bold py-3">
                            <i class="bi bi-gear me-2"></i> Cài đặt Hệ thống
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">Nhật ký Hoạt động (System Logs)</h6>
                    <span class="badge bg-secondary">Hôm nay</span>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <i class="bi bi-person-plus text-success me-2"></i>
                                    <strong>Admin</strong> đã cấp tài khoản Teacher cho <strong>Cô Phương</strong>
                                </div>
                                <span class="text-muted small">10 phút trước</span>
                            </div>
                        </li>
                        <li class="list-group-item py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <i class="bi bi-journal-plus text-primary me-2"></i>
                                    Giáo viên <strong>Thầy Tuấn</strong> vừa xuất bản <strong>Đề thi thử 101</strong>
                                </div>
                                <span class="text-muted small">1 giờ trước</span>
                            </div>
                        </li>
                        <li class="list-group-item py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <i class="bi bi-shield-exclamation text-danger me-2"></i>
                                    Phát hiện đăng nhập sai mật khẩu 5 lần từ tài khoản <strong>toan_hs</strong>
                                </div>
                                <span class="text-muted small">2 giờ trước</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\TTCN\Nhom13TTCN\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>