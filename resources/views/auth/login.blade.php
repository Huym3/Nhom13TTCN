<<<<<<< Updated upstream
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
=======
@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow border-0 mt-5">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0 fw-bold">ĐĂNG NHẬP</h4>
            </div>
            <div class="card-body p-4">
                
                @if ($errors->any())
                    <div class="alert alert-danger px-3 py-2">
                        <ul class="mb-0" style="padding-left: 15px;">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên đăng nhập (hoặc Email)</label>
                        <input type="text" name="email" class="form-control" placeholder="Nhập email..." required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu..." required>
                    </div>

                    <div class="text-end mb-3">
                        <a href="/forgot-password" class="text-decoration-none text-muted small">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3 shadow-sm">VÀO THI NGAY</button>
                    
                    <div class="text-center mt-3 border-top pt-3">
                        <span class="text-muted">Chưa có tài khoản?</span>
                        <a href="/register" class="text-decoration-none fw-bold text-primary">Đăng ký ngay</a>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
@endsection
>>>>>>> Stashed changes
