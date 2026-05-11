
{{-- resources/views/auth/register.blade.php                     --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Thi Thử THPT</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>🎓 Thi Thử THPT Quốc Gia</h2>
            <h3>Đăng ký tài khoản</h3>

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="hoTen">Họ và tên</label>
                    <input
                        type="text"
                        id="hoTen"
                        name="hoTen"
                        value="{{ old('hoTen') }}"
                        placeholder="Nguyễn Văn A"
                    >
                    @error('hoTen')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tenDangNhap">Tên đăng nhập</label>
                    <input
                        type="text"
                        id="tenDangNhap"
                        name="tenDangNhap"
                        value="{{ old('tenDangNhap') }}"
                        placeholder="vidu123"
                    >
                    @error('tenDangNhap')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="example@gmail.com"
                    >
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="matKhau">Mật khẩu</label>
                    <input
                        type="password"
                        id="matKhau"
                        name="matKhau"
                        placeholder="Ít nhất 6 ký tự"
                    >
                    @error('matKhau')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="matKhau_confirmation">Xác nhận mật khẩu</label>
                    <input
                        type="password"
                        id="matKhau_confirmation"
                        name="matKhau_confirmation"
                        placeholder="Nhập lại mật khẩu"
                    >
                </div>

                <button type="submit" class="btn-primary">Đăng ký</button>
            </form>

            <p class="auth-link">
                Đã có tài khoản?
                <a href="{{ route('login') }}">Đăng nhập</a>
            </p>
        </div>
    </div>
</body>
</html>