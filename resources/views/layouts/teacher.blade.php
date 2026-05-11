{{-- resources/views/layouts/teacher.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teacher') — Thi Thử THPT</title>
    <link rel="stylesheet" href="{{ asset('css/teacher.css') }}">
</head>
<body>

<nav class="navbar">
    <div class="nav-brand">🎓 Thi Thử THPT — Giáo viên</div>

    <div class="nav-menu">
        <a href="{{ route('teacher.dashboard') }}"
           class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
            🏠 Dashboard
        </a>
        <a href="{{ route('teacher.questions.index') }}"
           class="{{ request()->routeIs('teacher.questions.*') ? 'active' : '' }}">
            ❓ Câu hỏi
        </a>
        <a href="{{ route('teacher.exams.index') }}"
           class="{{ request()->routeIs('teacher.exams.*') ? 'active' : '' }}">
            📋 Đề thi
        </a>
    </div>

    <div class="nav-user">
        <span>{{ Session::get('hoTen') }}</span>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn-logout">Đăng xuất</button>
        </form>
    </div>
</nav>

<div class="main-content">
    @yield('content')
</div>

</body>
</html>