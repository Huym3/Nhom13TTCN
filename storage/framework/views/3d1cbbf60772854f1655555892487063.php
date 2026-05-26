

<?php $__env->startSection('title', 'Thêm Câu Hỏi Mới'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card shadow-sm" style="max-width: 900px; margin: auto;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Thêm Câu Hỏi Vào Ngân Hàng (Format 2025)</h5>
        </div>
        <div class="card-body">
            <form>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Chuyên đề</label>
                        <select class="form-select">
                            <option>Hàm số</option>
                            <option>Tích phân</option>
                            <option>Hình học không gian</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Mức độ</label>
                        <select class="form-select">
                            <option>Nhận biết</option>
                            <option>Thông hiểu</option>
                            <option>Vận dụng</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-danger">Dạng câu hỏi</label>
                        <select class="form-select border-danger" id="loaiCauHoi" onchange="doiFormNhap()">
                            <option value="TN">Phần I: Trắc nghiệm 4 đáp án</option>
                            <option value="DS">Phần II: Trắc nghiệm Đúng/Sai</option>
                            <option value="TLS">Phần III: Trả lời ngắn (Điền số)</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Nội dung câu hỏi (Hỗ trợ LaTeX \(\dots\) hoặc $$\dots$$):</label>
                    <textarea id="latexInput" class="form-control" rows="3" placeholder="Nhập đề bài vào đây..."></textarea>
                </div>

                <hr class="my-4">

                <div id="formTN" class="answer-form">
                    <h6 class="fw-bold text-primary mb-3">Nhập 4 phương án & Chọn đáp án đúng:</h6>
                    <div class="row g-2">
                        <div class="col-md-6 d-flex align-items-center mb-2">
                            <input class="form-check-input me-2 mt-0" type="radio" name="dapan_tn" checked>
                            <span class="fw-bold me-2">A.</span>
                            <input type="text" class="form-control" placeholder="Nội dung đáp án A">
                        </div>
                        <div class="col-md-6 d-flex align-items-center mb-2">
                            <input class="form-check-input me-2 mt-0" type="radio" name="dapan_tn">
                            <span class="fw-bold me-2">B.</span>
                            <input type="text" class="form-control" placeholder="Nội dung đáp án B">
                        </div>
                        <div class="col-md-6 d-flex align-items-center mb-2">
                            <input class="form-check-input me-2 mt-0" type="radio" name="dapan_tn">
                            <span class="fw-bold me-2">C.</span>
                            <input type="text" class="form-control" placeholder="Nội dung đáp án C">
                        </div>
                        <div class="col-md-6 d-flex align-items-center mb-2">
                            <input class="form-check-input me-2 mt-0" type="radio" name="dapan_tn">
                            <span class="fw-bold me-2">D.</span>
                            <input type="text" class="form-control" placeholder="Nội dung đáp án D">
                        </div>
                    </div>
                </div>

                <div id="formDS" class="answer-form d-none">
                    <h6 class="fw-bold text-success mb-3">Nhập 4 ý & Chọn Đ/S cho từng ý:</h6>
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr><th>Ý</th><th>Nội dung ý con</th><th class="text-center">Đáp án</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-center">a)</td>
                                <td><input type="text" class="form-control form-control-sm"></td>
                                <td class="text-center">
                                    <select class="form-select form-select-sm d-inline-block w-auto"><option>Đúng</option><option>Sai</option></select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-center">b)</td>
                                <td><input type="text" class="form-control form-control-sm"></td>
                                <td class="text-center">
                                    <select class="form-select form-select-sm d-inline-block w-auto"><option>Đúng</option><option>Sai</option></select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-center">c)</td>
                                <td><input type="text" class="form-control form-control-sm"></td>
                                <td class="text-center">
                                    <select class="form-select form-select-sm d-inline-block w-auto"><option>Đúng</option><option>Sai</option></select>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-center">d)</td>
                                <td><input type="text" class="form-control form-control-sm"></td>
                                <td class="text-center">
                                    <select class="form-select form-select-sm d-inline-block w-auto"><option>Đúng</option><option>Sai</option></select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="formTLS" class="answer-form d-none">
                    <h6 class="fw-bold text-danger mb-3">Nhập đáp án dạng số:</h6>
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Đáp án chính xác</label>
                            <input type="number" step="0.01" class="form-control" placeholder="VD: 8.5">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sai số cho phép (+/-)</label>
                            <input type="number" step="0.001" class="form-control" value="0.005">
                        </div>
                    </div>
                    <div class="form-text mt-2">Hệ thống sẽ chấm điểm tự động. Mặc định cho phép sai số nhỏ do làm tròn.</div>
                </div>

                <hr class="my-4">

                <div class="mb-3 p-3 bg-light border rounded">
                    <label class="form-label text-primary fw-bold">Xem trước đề bài:</label>
                    <div id="mathPreview" class="fs-5 mt-2 min-vh-25"></div>
                </div>

                <button type="button" class="btn btn-primary w-100 fw-bold fs-5 shadow-sm">Lưu vào Ngân hàng câu hỏi</button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"></script>
    
    <script>
        // 1. Script render LaTeX (Giữ nguyên như cũ)
        const input = document.getElementById('latexInput');
        const preview = document.getElementById('mathPreview');
        input.addEventListener('input', () => {
            preview.innerHTML = input.value.replace(/\n/g, '<br>');
            try {
                renderMathInElement(preview, {
                    delimiters: [ {left: "\\(", right: "\\)", display: false}, {left: "$$", right: "$$", display: true} ]
                });
            } catch (e) {}
        });

        // 2. Script ẩn/hiện Form nhập đáp án tùy theo loại câu hỏi
        function doiFormNhap() {
            let loai = document.getElementById('loaiCauHoi').value;
            
            // Tắt hết tất cả các form
            document.getElementById('formTN').classList.add('d-none');
            document.getElementById('formDS').classList.add('d-none');
            document.getElementById('formTLS').classList.add('d-none');
            
            // Bật đúng cái form đang được chọn
            if(loai === 'TN') {
                document.getElementById('formTN').classList.remove('d-none');
            } else if(loai === 'DS') {
                document.getElementById('formDS').classList.remove('d-none');
            } else if(loai === 'TLS') {
                document.getElementById('formTLS').classList.remove('d-none');
            }
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\TTCN\Nhom13TTCN\resources\views/teacher-question.blade.php ENDPATH**/ ?>