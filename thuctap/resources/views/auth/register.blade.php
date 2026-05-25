{{-- ============================================================ --}}
{{-- resources/views/auth/register.blade.php                    --}}
{{-- Glassmorphism · Light White-Gray · Frosted Glass           --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký — Thi Thử THPT</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">

        {{-- Header --}}
        <div class="auth-header">
            <div class="auth-icon-wrap">✏️</div>
            <h1>Tạo tài khoản</h1>
            <p>Tham gia hệ thống thi thử THPT miễn phí</p>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
        @endif

        {{-- Form --}}
        <form class="auth-form" method="POST" action="{{ route('register') }}">
            @csrf
            {{-- role hidden, synced by JS --}}
            <input type="hidden" id="roleInput" name="role" value="{{ old('role', 'Student') }}">

            {{-- Role selector --}}
            <div class="role-selector">
                <div class="role-option">
                    <input type="radio" id="role_student" name="_role_ui"
                           value="Student"
                           {{ old('role', 'Student') === 'Student' ? 'checked' : '' }}>
                    <label for="role_student" onclick="selectRole('Student')">
                        <span class="ri">👨‍🎓</span>
                        Học Sinh
                    </label>
                </div>
                <div class="role-option">
                    <input type="radio" id="role_teacher" name="_role_ui"
                           value="Teacher"
                           {{ old('role') === 'Teacher' ? 'checked' : '' }}>
                    <label for="role_teacher" onclick="selectRole('Teacher')">
                        <span class="ri">👨‍🏫</span>
                        Giáo Viên
                    </label>
                </div>
            </div>

            {{-- Teacher pending notice --}}
            <div class="teacher-warning" id="teacherWarning">
                ⚠️ Tài khoản giáo viên cần được Admin duyệt trước khi đăng nhập được.
            </div>

            {{-- Fields --}}
            <div class="auth-field">
                <label for="hoTen">Họ và tên</label>
                <input type="text" id="hoTen" name="hoTen"
                       value="{{ old('hoTen') }}"
                       placeholder="Nguyễn Văn A">
                @error('hoTen')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="auth-field">
                <label for="tenDangNhap">Tên đăng nhập</label>
                <input type="text" id="tenDangNhap" name="tenDangNhap"
                       value="{{ old('tenDangNhap') }}"
                       placeholder="vidu123">
                @error('tenDangNhap')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="auth-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="example@gmail.com">
                @error('email')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="auth-field">
                <label for="ngaySinh">Ngày sinh</label>
                <input type="date" id="ngaySinh" name="ngaySinh"
                       value="{{ old('ngaySinh') }}"
                       max="{{ now()->toDateString() }}">
                @error('ngaySinh')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="auth-field">
                <label for="matKhau">Mật khẩu</label>
                <input type="password" id="matKhau" name="matKhau"
                       placeholder="Ít nhất 6 ký tự">
                @error('matKhau')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="auth-field">
                <label for="matKhau_confirmation">Xác nhận mật khẩu</label>
                <input type="password" id="matKhau_confirmation"
                       name="matKhau_confirmation"
                       placeholder="Nhập lại mật khẩu">
            </div>

            <button type="submit" class="auth-submit">
                Đăng ký tài khoản →
            </button>
        </form>

        {{-- Links --}}
        <div class="auth-links">
            <p>Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
        </div>

    </div>
</div>

<script>
function selectRole(role) {
    document.getElementById('roleInput').value = role;

    // Sync radio UI state
    document.querySelectorAll('.role-option input[type="radio"]').forEach(r => {
        r.checked = (r.value === role);
    });

    // Teacher warning
    const warn = document.getElementById('teacherWarning');
    warn.classList.toggle('show', role === 'Teacher');
}

// Init on page load (handles old() value)
(function () {
    const saved = document.getElementById('roleInput').value;
    selectRole(saved);
})();
</script>
</body>
</html>