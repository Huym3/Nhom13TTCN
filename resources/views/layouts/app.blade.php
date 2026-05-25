<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Ôn Thi Toán</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @yield('css')
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/dashboard">Hệ Thống Ôn Thi Toán</a>
        
        <div>
            @auth
                @if(Auth::user()->Role == 'Admin')
                    <a href="/admin/dashboard" class="btn btn-sm btn-danger me-2"><i class="bi bi-shield-lock"></i> Admin Panel</a>
                    <a href="/admin/quan-ly-tai-khoan" class="btn btn-sm btn-outline-light me-2"><i class="bi bi-people"></i> Quản lý User</a>
                @endif

                @if(Auth::user()->Role == 'Teacher' || Auth::user()->Role == 'Admin')
                    <a href="/giao-vien/tao-cau-hoi" class="btn btn-sm btn-outline-light me-2">Ngân hàng câu hỏi</a>
                    <a href="/giao-vien/ghep-de" class="btn btn-sm btn-outline-light me-2">Ghép Đề Thi</a>
                    <a href="/giao-vien/quan-ly-de-thi" class="btn btn-sm btn-outline-light me-2">Quản lý Đề Thi</a>
                    <a href="/giao-vien/quan-ly-cau-hoi" class="btn btn-sm btn-outline-light me-2">Quản lý Câu hỏi</a>
                    <a href="/giao-vien/dashboard" class="btn btn-sm btn-info me-2">Bảng điều khiển</a>
                @endif

                @if(Auth::user()->Role == 'Student')
                    <a href="/dashboard" class="btn btn-sm btn-info me-2">Bảng điều khiển</a>
                    <a href="/danh-sach-de" class="btn btn-sm btn-outline-light me-2">Luyện đề</a>
                    <a href="/ket-qua-thi" class="btn btn-sm btn-outline-light me-2">Kết quả thi</a>
                    <a href="/phong-thi" class="btn btn-sm btn-outline-light me-2">Phòng thi</a>
                @endif
                
                <button class="btn btn-sm btn-danger ms-2">Đăng xuất</button>
            
            @else
                @if(!request()->is('login') && !request()->is('register'))
                    <a href="/login" class="btn btn-sm btn-light">Đăng nhập</a>
                    <a href="/register" class="btn btn-sm btn-outline-light ms-2">Đăng ký</a>
                @endif
            @endauth
        </div>
    </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>