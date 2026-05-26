

<?php $__env->startSection('title', 'Quản trị Hệ thống'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white fw-bold">
                Cấp tài khoản Giáo viên mới
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ tên Giáo viên</label>
                        <input type="text" class="form-control" placeholder="VD: Cô Phương">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên đăng nhập</label>
                        <input type="text" class="form-control" placeholder="VD: phuong_gv">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" placeholder="VD: phuong@truong.edu.vn">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger">Mật khẩu cấp phát</label>
                        <input type="text" class="form-control border-danger" value="Toan123456" readonly>
                        <div class="form-text">Mật khẩu mặc định hệ thống tự sinh.</div>
                    </div>
                    <button type="button" class="btn btn-primary w-100 fw-bold">Tạo & Cấp quyền Teacher</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                <span>Quản lý danh sách Người dùng</span>
                <input type="text" class="form-control form-control-sm w-25" placeholder="Tìm kiếm...">
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>ID</th>
                                <th>Họ tên</th>
                                <th>Tên đăng nhập</th>
                                <th>Chức vụ (Role)</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td class="fw-bold">Thầy Tuấn</td>
                                <td>tuan_math</td>
                                <td><span class="badge bg-success">Teacher</span></td>
                                <td><span class="badge bg-primary">Active</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger">Khóa</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td class="fw-bold">Nguyễn Văn A</td>
                                <td>nva_student</td>
                                <td><span class="badge bg-secondary">Student</span></td>
                                <td><span class="badge bg-primary">Active</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger">Khóa</button>
                                </td>
                            </tr>
                            <tr class="table-danger">
                                <td>3</td>
                                <td class="fw-bold text-muted">Trần Thị B</td>
                                <td class="text-muted">ttb_hack</td>
                                <td><span class="badge bg-secondary">Student</span></td>
                                <td><span class="badge bg-danger">Banned</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success">Mở khóa</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\TTCN\Nhom13TTCN\resources\views/admin/admin-users.blade.php ENDPATH**/ ?>