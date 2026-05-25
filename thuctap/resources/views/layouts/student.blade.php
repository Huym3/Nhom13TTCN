<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Thi Thử THPT')</title>
    <link rel="stylesheet" href="{{ asset('css/glass.css') }}">
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">🎓 Thi Thử THPT</div>
        <div class="nav-menu">
            <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('student.exams') }}"     class="{{ request()->routeIs('student.exams*') ? 'active' : '' }}">Đề Thi</a>
            <a href="{{ route('student.results.index') }}" class="{{ request()->routeIs('student.results*') ? 'active' : '' }}">Kết Quả</a>
        </div>
        <div class="nav-user">
            <span>👤 {{ session('hoTen') }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="btn-logout">Đăng xuất</button>
            </form>
        </div>
    </nav>

    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>