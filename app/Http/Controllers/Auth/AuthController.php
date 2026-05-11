<?php
// app/Http/Controllers/Auth/AuthController.php
// Tạo bằng lệnh: php artisan make:controller Auth/AuthController

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // ── Hiển thị trang đăng nhập ──────────────────────────
    public function showLogin()
    {
        // Nếu đã đăng nhập rồi thì redirect luôn
        if (Session::has('maNguoiDung')) {
            return $this->redirectByRole(Session::get('role'));
        }
        return view('auth.login');
    }

    // ── Xử lý đăng nhập ───────────────────────────────────
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'tenDangNhap' => 'required|string',
            'matKhau'     => 'required|string',
        ], [
            'tenDangNhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'matKhau.required'     => 'Vui lòng nhập mật khẩu.',
        ]);

        // Tìm user
        $user = NguoiDung::where('TenDangNhap', $request->tenDangNhap)->first();

        // Kiểm tra mật khẩu
        if (!$user || !Hash::check($request->matKhau, $user->MatKhau)) {
            return back()
                ->withInput($request->only('tenDangNhap'))
                ->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng.');
        }

        // Kiểm tra tài khoản bị banned
        if ($user->TrangThai === 'Banned') {
            return back()->with('error', 'Tài khoản của bạn đã bị khóa.');
        }

        // Lưu thông tin vào Session
        Session::put('maNguoiDung', $user->MaNguoiDung);
        Session::put('tenDangNhap', $user->TenDangNhap);
        Session::put('hoTen',       $user->HoTen);
        Session::put('role',        $user->Role);

        return $this->redirectByRole($user->Role);
    }

    // ── Hiển thị trang đăng ký ────────────────────────────
    public function showRegister()
    {
        return view('auth.register');
    }

    // ── Xử lý đăng ký ─────────────────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'hoTen'       => 'required|string|max:100',
            'tenDangNhap' => 'required|string|max:50|unique:NguoiDung,TenDangNhap',
            'email'       => 'required|email|unique:NguoiDung,Email',
            'matKhau'     => 'required|string|min:6|confirmed', // cần có matKhau_confirmation
        ], [
            'hoTen.required'            => 'Vui lòng nhập họ tên.',
            'tenDangNhap.required'      => 'Vui lòng nhập tên đăng nhập.',
            'tenDangNhap.unique'        => 'Tên đăng nhập đã tồn tại.',
            'email.required'            => 'Vui lòng nhập email.',
            'email.unique'              => 'Email đã được sử dụng.',
            'matKhau.min'               => 'Mật khẩu ít nhất 6 ký tự.',
            'matKhau.confirmed'         => 'Xác nhận mật khẩu không khớp.',
        ]);

        NguoiDung::create([
            'HoTen'       => $request->hoTen,
            'TenDangNhap' => $request->tenDangNhap,
            'Email'       => $request->email,
            'MatKhau'     => Hash::make($request->matKhau),
            'Role'        => 'Student', // mặc định là học sinh
            'TrangThai'   => 'Active',
        ]);

        return redirect()->route('login')
                         ->with('success', 'Đăng ký thành công! Hãy đăng nhập.');
    }

    // ── Đăng xuất ─────────────────────────────────────────
    public function logout()
    {
        Session::flush(); // Xoá toàn bộ session
        return redirect()->route('login')
                         ->with('success', 'Đã đăng xuất.');
    }

    // ── Helper: redirect theo role ─────────────────────────
    private function redirectByRole($role)
    {
        return match($role) {
            'Student' => redirect()->route('student.dashboard'),
            'Teacher' => redirect()->route('teacher.dashboard'),
            'Admin'   => redirect()->route('admin.dashboard'),
            default   => redirect()->route('login'),
        };
    }
}