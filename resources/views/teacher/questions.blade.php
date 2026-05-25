@extends('layouts.app')

@section('title', 'Quản lý Ngân hàng Câu hỏi')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h4 class="fw-bold text-primary mb-0"><i class="bi bi-database"></i> NGÂN HÀNG CÂU HỎI TOÁN</h4>
        <a href="/giao-vien/tao-cau-hoi" class="btn btn-primary fw-bold">
            <i class="bi bi-plus-circle me-1"></i> Thêm câu hỏi mới
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm w-auto">
                    <option value="">Tất cả chuyên đề</option>
                    <option value="Hàm số">Hàm số</option>
                    <option value="Oxyz">Hình học Oxyz</option>
                </select>
                <select class="form-select form-select-sm w-auto">
                    <option value="">Mọi độ khó</option>
                    <option value="Dễ">Dễ</option>
                    <option value="Vừa">Vừa</option>
                    <option value="Khó">Khó</option>
                </select>
            </div>
            <div class="input-group w-25">
                <input type="text" class="form-control form-control-sm" placeholder="Tìm ID, nội dung...">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th style="width: 40%;">Nội dung câu hỏi</th>
                            <th>Chuyên đề</th>
                            <th>Độ khó</th>
                            <th>Người tạo</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4 text-muted">#105</td>
                            <td>
                                <div class="text-truncate" style="max-width: 300px;" title="Cho hàm số y = f(x) có bảng biến thiên như sau...">
                                    Cho hàm số y = f(x) có bảng biến thiên như sau...
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border">Hàm số</span></td>
                            <td><span class="badge bg-success">Dễ</span></td>
                            <td class="small">Thầy Tuấn</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" title="Sửa"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger" title="Xóa"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted">#106</td>
                            <td>
                                <div class="text-truncate" style="max-width: 300px;">
                                    Trong không gian Oxyz, cho mặt cầu (S) tâm I(1; -2; 3)...
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border">Hình Oxyz</span></td>
                            <td><span class="badge bg-danger">Khó</span></td>
                            <td class="small">Cô Phương</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            <nav><ul class="pagination pagination-sm mb-0 justify-content-end">
                <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">Sau</a></li>
            </ul></nav>
        </div>
    </div>
</div>
@endsection