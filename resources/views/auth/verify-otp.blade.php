{{-- ============================================================ --}}
{{-- resources/views/auth/verify-otp.blade.php                    --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận OTP - Thi Thử THPT</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-page">
        <div class="auth-box">
            
            <div class="auth-header">
                <div class="auth-icon-wrap">🛡️</div>
                <h1>Xác nhận mã OTP</h1>
                <p>Chúng tôi đã gửi mã 6 số đến email của bạn.<br>Vui lòng nhập mã để tiếp tục.</p>
            </div>

            {{-- Thông báo lỗi --}}
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            {{-- Thông báo thành công --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('password.verify-otp.post') }}" class="auth-form">
                @csrf

                <div class="auth-field">
                    <label for="otp">Mã OTP</label>
                    <input
                        type="text"
                        id="otp"
                        name="otp"
                        placeholder="Nhập 6 số OTP..."
                        maxlength="6"
                        pattern="[0-9]{6}"
                        autofocus
                        required
                    >
                    @error('otp')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="auth-submit">Xác nhận</button>
            </form>

            <div class="auth-links">
                <a href="{{ route('password.request') }}">← Quay lại</a>
            </div>
            
        </div>
    </div>
</body>
</html>