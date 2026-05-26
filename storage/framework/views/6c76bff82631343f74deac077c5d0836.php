

<?php $__env->startSection('title', 'Kho Đề Thi Trực Tuyến'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-md-5 pb-5">
    
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 mt-3">
        <div>
            <h4 class="fw-bold text-uppercase text-primary mb-1">Kho Đề Thi Toán 2025</h4>
            <p class="text-muted mb-0">Hơn 500+ đề thi chuẩn cấu trúc đang chờ bạn chinh phục</p>
        </div>
        <div class="input-group mt-3 mt-md-0" style="max-width: 400px;">
            <input type="text" class="form-control border-primary" placeholder="Nhập tên đề, mã đề...">
            <button class="btn btn-primary fw-bold px-4">TÌM</button>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
        
        <?php for($i = 1; $i <= 15; $i++): ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0 exam-card">
                <div class="card-body d-flex flex-column">
                    <span class="badge bg-danger align-self-start mb-2">Mới nhất</span>
                    
                    <h6 class="card-title fw-bold text-dark mt-1">Đề ôn tập bám sát BGD số <?php echo e($i); ?></h6>
                    
                    <div class="mt-2 text-muted small">
                        <div><i class="bi bi-clock"></i> 90 phút</div>
                        <div><i class="bi bi-list-ol"></i> 22 câu hỏi</div>
                    </div>
                    
                    <a href="/phong-thi" class="btn btn-outline-primary w-100 fw-bold mt-auto pt-2 pb-2">VÀO THI NGAY</a>
                </div>
            </div>
        </div>
        <?php endfor; ?>

    </div>

    <div class="d-flex justify-content-center mt-5">
        <nav>
            <ul class="pagination pagination-lg">
                <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
            </ul>
        </nav>
    </div>
</div>

<style>
    /* Hiệu ứng hover cho thẻ đề thi nảy lên một chút */
    .exam-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .exam-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\TTCN\Nhom13TTCN\resources\views/student/student-exams.blade.php ENDPATH**/ ?>