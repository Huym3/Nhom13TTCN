

<?php $__env->startSection('title', 'Phòng Thi TOÁN (Format 2025)'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
    <style>
        .question-box { border-left: 4px solid #0d6efd; background-color: #fff; }
        .part-title { background-color: #e9ecef; border-radius: 5px; padding: 10px; font-weight: bold; text-transform: uppercase; }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-8 mb-4">
            
            <div class="part-title mb-3">Phần I. Câu trắc nghiệm nhiều phương án lựa chọn</div>
            <p class="text-muted fst-italic mb-4">Thí sinh trả lời từ câu 1 đến câu 12. Mỗi câu hỏi thí sinh chỉ chọn một phương án.</p>
            
            <div class="card shadow-sm mb-4 question-box">
                <div class="card-body">
                    <h6 class="fw-bold">Câu 1:</h6>
                    <p>Cho hàm số \( y = \frac{2x + 1}{x - 1} \). Tính đạo hàm của hàm số.</p>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="phan1_cau1" id="c1_A">
                                <label class="form-check-label" for="c1_A">A. \( y' = \frac{-3}{(x - 1)^2} \)</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="phan1_cau1" id="c1_B">
                                <label class="form-check-label" for="c1_B">B. \( y' = \frac{3}{(x - 1)^2} \)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="part-title mb-3 mt-5">Phần II. Câu trắc nghiệm đúng sai</div>
            <p class="text-muted fst-italic mb-4">Thí sinh trả lời từ câu 1 đến câu 4. Trong mỗi ý a), b), c), d) ở mỗi câu, thí sinh chọn đúng hoặc sai.</p>

            <div class="card shadow-sm mb-4 question-box" style="border-left-color: #198754;">
                <div class="card-body">
                    <h6 class="fw-bold">Câu 1 (Phần II):</h6>
                    <p>Cho hình chóp S.ABCD có đáy ABCD là hình vuông cạnh \( a \), \( SA \perp (ABCD) \) và \( SA = a\sqrt{2} \). Khi đó:</p>
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Ý</th>
                                <th>Nội dung</th>
                                <th style="width: 80px;">Đúng</th>
                                <th style="width: 80px;">Sai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center fw-bold">a)</td>
                                <td>Thể tích khối chóp S.ABCD là \( V = \frac{a^3\sqrt{2}}{3} \)</td>
                                <td class="text-center"><input class="form-check-input" type="radio" name="p2_c1_a" value="1"></td>
                                <td class="text-center"><input class="form-check-input" type="radio" name="p2_c1_a" value="0"></td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">b)</td>
                                <td>Góc giữa SC và mặt phẳng (ABCD) bằng \( 45^\circ \)</td>
                                <td class="text-center"><input class="form-check-input" type="radio" name="p2_c1_b" value="1"></td>
                                <td class="text-center"><input class="form-check-input" type="radio" name="p2_c1_b" value="0"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="part-title mb-3 mt-5">Phần III. Câu trắc nghiệm trả lời ngắn</div>
            <p class="text-muted fst-italic mb-4">Thí sinh trả lời từ câu 1 đến câu 6. Thí sinh điền đáp án dạng số vào ô trống.</p>

            <div class="card shadow-sm mb-4 question-box" style="border-left-color: #dc3545;">
                <div class="card-body">
                    <h6 class="fw-bold">Câu 1 (Phần III):</h6>
                    <p>Một người gửi tiết kiệm 100 triệu đồng với lãi suất 6%/năm. Hỏi sau ít nhất bao nhiêu năm người đó thu được gấp đôi số tiền ban đầu? (Làm tròn đến chữ số hàng đơn vị).</p>
                    <div class="mt-3">
                        <label class="form-label fw-bold text-danger">Đáp án của bạn:</label>
                        <input type="number" step="0.01" class="form-control" style="max-width: 200px;" placeholder="Nhập số...">
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h5 class="mb-0">THỜI GIAN CÒN LẠI</h5>
                    <h2 class="display-5 fw-bold text-warning mt-2 mb-0" id="countdown">90:00</h2>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Tiến độ làm bài</h6>
                    
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <button class="btn btn-outline-primary btn-sm" style="width: 40px;">1</button>
                        <button class="btn btn-outline-success btn-sm" style="width: 40px;">2</button>
                        <button class="btn btn-outline-danger btn-sm" style="width: 40px;">3</button>
                    </div>

                    <button class="btn btn-danger w-100 py-2 fw-bold fs-5 shadow">NỘP BÀI THI</button>
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
                delimiters: [
                    {left: "\\(", right: "\\)", display: false},
                    {left: "$$", right: "$$", display: true}
                ],
                throwOnError: false
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\TTCN\Nhom13TTCN\resources\views/room.blade.php ENDPATH**/ ?>