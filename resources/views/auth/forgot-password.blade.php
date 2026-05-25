{{-- resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu — Thi Thử THPT</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">

        <div class="auth-header">
            <div class="auth-icon-wrap">🔑</div>
            <h1>Quên mật khẩu</h1>
            <p>Nhập email để nhận mã OTP đặt lại mật khẩu</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        <form class="auth-form" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="auth-field">
                <label for="email">Địa chỉ Email</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="email@example.com"
                       autofocus>
                @error('email')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="auth-submit">
                Gửi mã OTP →
            </button>
        </form>

        <div class="auth-links">
            <p>Nhớ mật khẩu rồi? <a href="{{ route('login') }}">Đăng nhập</a></p>
        </div>

    </div>
</div>
</body>
</html>