{{-- ============================================================ --}}
{{-- resources/views/auth/login.blade.php                       --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Thi Thử THPT</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>🎓 Thi Thử THPT Quốc Gia</h2>
            <h3>Đăng nhập</h3>

            {{-- Thông báo lỗi --}}
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            {{-- Thông báo thành công (sau đăng ký) --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="tenDangNhap">Tên đăng nhập</label>
                    <input
                        type="text"
                        id="tenDangNhap"
                        name="tenDangNhap"
                        value="{{ old('tenDangNhap') }}"
                        placeholder="Nhập tên đăng nhập"
                        autofocus
                    >
                    @error('tenDangNhap')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="matKhau">Mật khẩu</label>
                    <input
                        type="password"
                        id="matKhau"
                        name="matKhau"
                        placeholder="Nhập mật khẩu"
                    >
                    @error('matKhau')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">Đăng nhập</button>
            </form>

            <p class="auth-link">
                Chưa có tài khoản?
                <a href="{{ route('register') }}">Đăng ký ngay</a>
            </p>
        </div>
    </div>
</body>
</html>