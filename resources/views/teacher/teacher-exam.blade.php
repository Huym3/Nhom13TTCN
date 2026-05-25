@extends('layouts.app')

@section('title', 'Ghép Đề Thi (Format 2025)')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">1. Thông tin Đề thi</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên đề thi</label>
                        <input type="text" class="form-control" placeholder="VD: Đề thi thử THPT Quốc gia 2025...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Thời gian (phút)</label>
                        <select class="form-select">
                            <option>45</option>
                            <option selected>90</option>
                        </select>
                    </div>
                    <hr>
                    <h6 class="fw-bold text-primary">Cấu trúc đề (Tiến độ):</h6>
                    <ul class="list-group list-group-flush mb-3 text-sm">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Phần I (Trắc nghiệm)
                            <span class="badge bg-primary rounded-pill">0 / 12</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Phần II (Đúng/Sai)
                            <span class="badge bg-success rounded-pill">0 / 4</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Phần III (Trả lời ngắn)
                            <span class="badge bg-danger rounded-pill">0 / 6</span>
                        </li>
                    </ul>
                    <button class="btn btn-success w-100 fw-bold shadow-sm">Lưu & Xuất bản Đề thi</button>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">2. Chọn câu hỏi từ Ngân hàng</h5>
                </div>
                <div class="card-body p-0">
                    
                    <ul class="nav nav-tabs bg-light pt-2 px-2" id="examTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold text-primary" id="p1-tab" data-bs-toggle="tab" data-bs-target="#p1" type="button" role="tab">
                                Phần I (0.25đ/câu)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold text-success" id="p2-tab" data-bs-toggle="tab" data-bs-target="#p2" type="button" role="tab">
                                Phần II (1.0đ/câu)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold text-danger" id="p3-tab" data-bs-toggle="tab" data-bs-target="#p3" type="button" role="tab">
                                Phần III (0.5đ/câu)
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="examTabsContent">
                        
                        <div class="tab-pane fade show active p-3" id="p1" role="tabpanel">
                            <div class="row mb-3 g-2">
                                <div class="col-md-5"><input type="text" class="form-control" placeholder="Tìm kiếm câu hỏi Phần I..."></div>
                                <div class="col-md-4">
                                    <select class="form-select"><option>-- Tất cả chuyên đề --</option></select>
                                </div>
                                <div class="col-md-3"><button class="btn btn-outline-primary w-100">Lọc</button></div>
                            </div>
                            
                            <div class="table-responsive border rounded" style="max-height: 50vh; overflow-y: auto;">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">Chọn</th>
                                            <th>Nội dung câu hỏi (Dạng 4 Đáp án)</th>
                                            <th style="width: 120px;">Mức độ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center"><input class="form-check-input border-primary" type="checkbox"></td>
                                            <td>Cho hàm số \( y = \frac{2x+1}{x-1} \). Tính đạo hàm của hàm số.</td>
                                            <td><span class="badge bg-info text-dark">Nhận biết</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade p-3" id="p2" role="tabpanel">
                            <div class="table-responsive border rounded" style="max-height: 50vh; overflow-y: auto;">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">Chọn</th>
                                            <th>Nội dung câu hỏi (Dạng Đúng/Sai 4 ý)</th>
                                            <th style="width: 120px;">Mức độ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center"><input class="form-check-input border-success" type="checkbox"></td>
                                            <td>Cho hình chóp S.ABCD có đáy là hình vuông, \( SA \perp (ABCD) \)...</td>
                                            <td><span class="badge bg-warning text-dark">Vận dụng</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade p-3" id="p3" role="tabpanel">
                            <div class="table-responsive border rounded" style="max-height: 50vh; overflow-y: auto;">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">Chọn</th>
                                            <th>Nội dung câu hỏi (Dạng Trả lời ngắn số)</th>
                                            <th style="width: 120px;">Mức độ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center"><input class="form-check-input border-danger" type="checkbox"></td>
                                            <td>Một người gửi tiết kiệm 100 triệu đồng với lãi suất 6%/năm...</td>
                                            <td><span class="badge bg-danger">Vận dụng cao</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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
@endsection