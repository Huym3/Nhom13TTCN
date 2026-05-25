{{-- ============================================================ --}}
{{-- resources/views/auth/reset-password.blade.php         --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - Thi Thử THPT</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-page">
        <div class="auth-box">

            <div class="auth-header">
                <div class="auth-icon-wrap">🔑</div>
                <h1>Đặt lại mật khẩu</h1>
                <p>Nhập mật khẩu mới của bạn bên dưới</p>
            </div>

            {{-- Thông báo lỗi --}}
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            {{-- Thông báo thành công --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" class="auth-form">
                @csrf

                {{-- Token ẩn --}}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="auth-field">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $email) }}"
                        placeholder="Nhập email của bạn..."
                        autofocus
                    >
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="auth-field">
                    <label for="password">Mật khẩu mới</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Ít nhất 6 ký tự..."
                    >
                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="auth-field">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Nhập lại mật khẩu mới..."
                    >
                    @error('password_confirmation')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="auth-submit">Đặt lại mật khẩu</button>
            </form>

            <div class="auth-links">
                <a href="{{ route('login') }}">← Quay lại đăng nhập</a>
            </div>
            
        </div>
    </div>
</body>
</html>