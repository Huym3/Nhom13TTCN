

<?php $__env->startSection('title', 'Tổng quan Hệ thống Giáo viên'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 pb-5">
    <h3 class="fw-bold text-dark mt-3 mb-4">BẢNG ĐIỀU KHIỂN GIÁO VIÊN</h3>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-6 fw-bold text-uppercase mb-1">Tổng câu hỏi</div>
                            <div class="fs-2 fw-bold">1,245</div>
                        </div>
                        <i class="bi bi-database fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-6 fw-bold text-uppercase mb-1">Đề đang mở</div>
                            <div class="fs-2 fw-bold">12</div>
                        </div>
                        <i class="bi bi-journal-check fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-6 fw-bold text-uppercase mb-1">Học sinh đã thi</div>
                            <div class="fs-2 fw-bold">850</div>
                        </div>
                        <i class="bi bi-people fs-1 text-dark-50" style="opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-6 fw-bold text-uppercase mb-1">Điểm TB Hệ thống</div>
                            <div class="fs-2 fw-bold">6.8</div>
                        </div>
                        <i class="bi bi-graph-up-arrow fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                    <h6 class="fw-bold mb-0 text-primary">Tình hình làm bài gần đây</h6>
                    <a href="/giao-vien/quan-ly-de-thi" class="btn btn-sm btn-outline-secondary">Xem tất cả đề</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Tên đề thi</th>
                                    <th>Số lượt thi</th>
                                    <th>Điểm cao nhất</th>
                                    <th>Điểm trung bình</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4 fw-bold">Đề thi thử THPT Quốc gia Lần 1</td>
                                    <td>128</td>
                                    <td><span class="text-success fw-bold">10.0</span></td>
                                    <td><span class="badge bg-warning text-dark">6.5</span></td>
                                </tr>
                                <tr>
                                    <td class="ps-4 fw-bold">Đề kiểm tra Hình học Oxyz</td>
                                    <td>45</td>
                                    <td><span class="text-success fw-bold">9.5</span></td>
                                    <td><span class="badge bg-danger">4.2</span> (Khó)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-primary">Thao tác nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="/giao-vien/tao-cau-hoi" class="btn btn-outline-primary text-start fw-bold py-3">
                            <i class="bi bi-plus-circle me-2"></i> Soạn câu hỏi mới
                        </a>
                        <a href="/giao-vien/ghep-de" class="btn btn-outline-success text-start fw-bold py-3">
                            <i class="bi bi-file-earmark-plus me-2"></i> Ghép đề thi mới
                        </a>
                        <a href="/giao-vien/quan-ly-cau-hoi" class="btn btn-outline-secondary text-start fw-bold py-3">
                            <i class="bi bi-folder2-open me-2"></i> Duyệt ngân hàng câu hỏi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\TTCN\Nhom13TTCN\resources\views/teacher/dashboard.blade.php ENDPATH**/ ?>