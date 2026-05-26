{{-- ============================================================ --}}
{{-- resources/views/auth/login.blade.php                       --}}
{{-- Glassmorphism · Light White-Gray · Frosted Glass           --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập — Thi Thử THPT</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">

        {{-- Header --}}
        <div class="auth-header">
            <div class="auth-icon-wrap">🎓</div>
            <h1>Thi Thử THPT Quốc Gia</h1>
            <p>Đăng nhập để tiếp tục học tập</p>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        {{-- Form --}}
        <form class="auth-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="auth-field">
                <label for="tenDangNhap">Tên đăng nhập</label>
                <input
                    type="text"
                    id="tenDangNhap"
                    name="tenDangNhap"
                    value="{{ old('tenDangNhap') }}"
                    placeholder="Nhập tên đăng nhập của bạn"
                    autocomplete="username"
                    autofocus
                >
                @error('tenDangNhap')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="auth-field">
                <label for="matKhau">Mật khẩu</label>
                <input
                    type="password"
                    id="matKhau"
                    name="matKhau"
                    placeholder="Nhập mật khẩu"
                    autocomplete="current-password"
                >
                @error('matKhau')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="auth-submit">
                Đăng nhập →
            </button>
        </form>

        {{-- Links --}}
        <div class="auth-links">
            <p>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></p>
            <p><a href="{{ route('password.request') }}">Quên mật khẩu?</a></p>
        </div>

    </div>
</div>
</body>
</html>