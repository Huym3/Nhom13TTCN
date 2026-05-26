

<?php $__env->startSection('title', 'Kết quả làm bài'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
    <style>
        .correct-answer { background-color: #d1e7dd; border: 1px solid #198754; border-radius: 5px; padding: 5px; }
        .wrong-answer { background-color: #f8d7da; border: 1px solid #dc3545; border-radius: 5px; padding: 5px; }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container pb-5">
        
        <div class="card shadow border-0 mb-5">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0 fw-bold">KẾT QUẢ BÀI THI</h4>
                <div class="fs-5">Đề thi thử THPT Quốc gia 2025 - Lần 1</div>
            </div>
            <div class="card-body p-4">
                <div class="row text-center">
                    <div class="col-md-3 border-end">
                        <div class="text-muted mb-1">Tổng điểm</div>
                        <div class="display-4 fw-bold text-success">8.60</div>
                    </div>
                    <div class="col-md-3 border-end">
                        <div class="text-muted mb-1">Thời gian làm bài</div>
                        <div class="fs-3 fw-bold">45:12 <span class="fs-6 text-muted fw-normal">/ 90 phút</span></div>
                    </div>
                    <div class="col-md-6 text-start ps-4">
                        <h6 class="fw-bold text-muted mb-3">Phân tích điểm chi tiết:</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phần I (Trắc nghiệm):</span> <strong class="text-primary">2.50 / 3.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phần II (Đúng/Sai):</span> <strong class="text-success">3.10 / 4.00</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Phần III (Trả lời ngắn):</span> <strong class="text-danger">3.00 / 3.00</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="fw-bold mb-4 border-bottom pb-2">Chi tiết bài làm</h4>

        <h5 class="fw-bold text-primary mb-3">Phần I. Câu trắc nghiệm nhiều phương án lựa chọn</h5>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h6 class="fw-bold">Câu 1: <span class="badge bg-danger ms-2">Sai (0.00đ)</span></h6>
                </div>
                <p>Cho hàm số \( y = \frac{2x + 1}{x - 1} \). Tính đạo hàm của hàm số.</p>
                <div class="row mt-3">
                    <div class="col-md-6 mb-2">
                        <div class="wrong-answer">
                            <span class="fw-bold text-danger">A.</span> \( y' = \frac{3}{(x - 1)^2} \) 
                            <i class="float-end text-danger fw-bold">(Bạn chọn)</i>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="correct-answer">
                            <span class="fw-bold text-success">B.</span> \( y' = \frac{-3}{(x - 1)^2} \)
                            <i class="float-end text-success fw-bold">(Đáp án đúng)</i>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3 mb-0 p-2 text-sm">
                    <strong>Giải thích:</strong> Áp dụng công thức tính nhanh đạo hàm phân thức bậc nhất trên bậc nhất \( y = \frac{ax+b}{cx+d} \Rightarrow y' = \frac{ad-bc}{(cx+d)^2} \).
                </div>
            </div>
        </div>

        <h5 class="fw-bold text-success mt-5 mb-3">Phần II. Câu trắc nghiệm đúng sai</h5>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold">Câu 1 (Phần II): <span class="badge bg-warning text-dark ms-2">Đúng một phần (0.50đ)</span></h6>
                <p>Cho hình chóp S.ABCD có đáy ABCD là hình vuông cạnh \( a \), \( SA \perp (ABCD) \) và \( SA = a\sqrt{2} \).</p>
                
                <table class="table table-bordered align-middle text-center mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>Ý</th>
                            <th class="text-start">Nội dung</th>
                            <th>Bạn chọn</th>
                            <th>Đáp án đúng</th>
                            <th>Kết quả</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold">a)</td>
                            <td class="text-start">Thể tích khối chóp S.ABCD là \( V = \frac{a^3\sqrt{2}}{3} \)</td>
                            <td class="text-primary fw-bold">Đúng</td>
                            <td class="text-success fw-bold">Đúng</td>
                            <td><span class="text-success fw-bold">✓</span></td>
                        </tr>
                        <tr class="table-danger">
                            <td class="fw-bold">b)</td>
                            <td class="text-start">Góc giữa SC và mặt phẳng (ABCD) bằng \( 45^\circ \)</td>
                            <td class="text-danger fw-bold">Đúng</td>
                            <td class="text-success fw-bold">Sai</td>
                            <td><span class="text-danger fw-bold">✗</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <h5 class="fw-bold text-danger mt-5 mb-3">Phần III. Câu trắc nghiệm trả lời ngắn</h5>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold">Câu 1 (Phần III): <span class="badge bg-success ms-2">Đúng (0.50đ)</span></h6>
                <p>Một người gửi tiết kiệm 100 triệu đồng với lãi suất 6%/năm. Hỏi sau ít nhất bao nhiêu năm người đó thu được gấp đôi số tiền ban đầu?</p>
                <div class="d-flex align-items-center mt-3 border p-3 rounded bg-light">
                    <div class="me-5">
                        <span class="text-muted">Bạn đã trả lời:</span>
                        <h4 class="text-success fw-bold mb-0">12</h4>
                    </div>
                    <div>
                        <span class="text-muted">Đáp án chính xác:</span>
                        <h4 class="text-primary fw-bold mb-0">12 <span class="fs-6 text-muted fw-normal">(Chấp nhận sai số ±0.00)</span></h4>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            renderMathInElement(document.body, {
                delimiters: [ {left: "\\(", right: "\\)", display: false}, {left: "$$", right: "$$", display: true} ],
                throwOnError: false
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\TTCN\Nhom13TTCN\resources\views/student/result.blade.php ENDPATH**/ ?>