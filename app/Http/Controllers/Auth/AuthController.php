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

        // Kiểm tra tài khoản bị chặn
        if ($user->TrangThai === 'Banned') {
            return back()->with('error', 'Tài khoản của bạn đã bị khóa.');
        }

        // Kiểm tra nếu là giáo viên, phải được duyệt trước
        if ($user->Role === 'Teacher' && $user->TeacherStatus !== 'Approved') {
            return back()->with('error', 'Tài khoản giáo viên của bạn đang chờ duyệt từ Admin. Vui lòng quay lại sau.');
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

    // ── Hiển thị trang quên mật khẩu ──────────────────────
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // ── Xử lý gửi email reset password ────────────────────
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:NguoiDung,Email',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email'    => 'Email không hợp lệ.',
            'email.exists'   => 'Email này không tồn tại trong hệ thống.',
        ]);

        // Tạo OTP 6 số ngẫu nhiên (để test: luôn là 999999)
        $otp = '999999';

        // Lưu OTP vào session với email (thời hạn 10 phút)
        Session::put('reset_otp', [
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10)
        ]);

        // TODO: Implement gửi email reset password
        // Hiện tại chỉ hiển thị thông báo thành công
        return redirect()->route('password.verify-otp')
                         ->with('success', 'Mã OTP đã được gửi đến email của bạn');
    }

    // ── Hiển thị trang nhập OTP ───────────────────────────
    public function showVerifyOtp()
    {
        // Kiểm tra có session reset_otp không
        if (!Session::has('reset_otp')) {
            return redirect()->route('password.request')
                             ->with('error', 'Vui lòng yêu cầu đặt lại mật khẩu trước.');
        }

        // Kiểm tra OTP còn hạn không
        $resetData = Session::get('reset_otp');
        if (now()->greaterThan($resetData['expires_at'])) {
            Session::forget('reset_otp');
            return redirect()->route('password.request')
                             ->with('error', 'Mã OTP đã hết hạn. Vui lòng yêu cầu lại.');
        }

        return view('auth.verify-otp');
    }

    // ── Xử lý verify OTP ──────────────────────────────────
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Vui lòng nhập mã OTP.',
            'otp.size'     => 'Mã OTP phải có 6 chữ số.',
        ]);

        // Kiểm tra session
        if (!Session::has('reset_otp')) {
            return redirect()->route('password.request')
                             ->with('error', 'Phiên đặt lại mật khẩu đã hết hạn.');
        }

        $resetData = Session::get('reset_otp');

        // Kiểm tra OTP còn hạn
        if (now()->greaterThan($resetData['expires_at'])) {
            Session::forget('reset_otp');
            return redirect()->route('password.request')
                             ->with('error', 'Mã OTP đã hết hạn.');
        }

        // Kiểm tra OTP
        if ($request->otp !== $resetData['otp']) {
            return back()->with('error', 'Mã OTP không đúng.');
        }

        // OTP đúng, tạo token và chuyển đến trang reset
        $token = bin2hex(random_bytes(32));
        Session::put('reset_verified', [
            'email' => $resetData['email'],
            'token' => $token,
            'expires_at' => now()->addMinutes(30)
        ]);

        // Xóa session OTP
        Session::forget('reset_otp');

        return redirect()->route('password.reset', $token);
    }

    // ── Hiển thị trang đặt lại mật khẩu ───────────────────
    public function showResetPassword(Request $request, $token)
    {
        // Kiểm tra token hợp lệ
        if (!Session::has('reset_verified')) {
            return redirect()->route('password.request')
                             ->with('error', 'Liên kết đặt lại mật khẩu không hợp lệ.');
        }

        $verifiedData = Session::get('reset_verified');

        if ($token !== $verifiedData['token'] || now()->greaterThan($verifiedData['expires_at'])) {
            Session::forget('reset_verified');
            return redirect()->route('password.request')
                             ->with('error', 'Liên kết đặt lại mật khẩu đã hết hạn.');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $verifiedData['email']
        ]);
    }

    // ── Xử lý đặt lại mật khẩu ────────────────────────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.required'    => 'Vui lòng nhập email.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min'      => 'Mật khẩu ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Kiểm tra session verified
        if (!Session::has('reset_verified')) {
            return redirect()->route('password.request')
                             ->with('error', 'Phiên đặt lại mật khẩu không hợp lệ.');
        }

        $verifiedData = Session::get('reset_verified');

        // Kiểm tra token và email
        if ($request->token !== $verifiedData['token'] ||
            $request->email !== $verifiedData['email'] ||
            now()->greaterThan($verifiedData['expires_at'])) {
            Session::forget('reset_verified');
            return redirect()->route('password.request')
                             ->with('error', 'Phiên đặt lại mật khẩu đã hết hạn.');
        }

        // Cập nhật mật khẩu
        $user = NguoiDung::where('Email', $request->email)->first();
        $user->MatKhau = Hash::make($request->password);
        $user->save();

        // Xóa session
        Session::forget('reset_verified');

        return redirect()->route('login')
                         ->with('success', 'Mật khẩu đã được đặt lại thành công! Hãy đăng nhập với mật khẩu mới.');
    }

    // ── Xử lý đăng ký (học sinh hoặc giáo viên) ──────────
    public function register(Request $request)
    {
        $request->validate([
            'role'        => 'required|in:Student,Teacher',
            'hoTen'       => 'required|string|max:100',
            'tenDangNhap' => 'required|string|max:50|unique:NguoiDung,TenDangNhap',
            'email'       => 'required|email|unique:NguoiDung,Email',
            'ngaySinh'    => 'required|date|before_or_equal:today',
            'matKhau'     => 'required|string|min:6|confirmed', // cần có matKhau_confirmation
        ], [
            'role.required'             => 'Vui lòng chọn loại tài khoản.',
            'hoTen.required'            => 'Vui lòng nhập họ tên.',
            'tenDangNhap.required'      => 'Vui lòng nhập tên đăng nhập.',
            'tenDangNhap.unique'        => 'Tên đăng nhập đã tồn tại.',
            'email.required'            => 'Vui lòng nhập email.',
            'email.email'               => 'Email không hợp lệ.',
            'email.unique'              => 'Email đã được sử dụng.',
            'ngaySinh.required'         => 'Vui lòng nhập ngày sinh.',
            'ngaySinh.date'             => 'Ngày sinh không hợp lệ.',
            'ngaySinh.before_or_equal'  => 'Ngày sinh không được lớn hơn ngày hiện tại.',
            'matKhau.required'          => 'Vui lòng nhập mật khẩu.',
            'matKhau.min'               => 'Mật khẩu ít nhất 6 ký tự.',
            'matKhau.confirmed'         => 'Xác nhận mật khẩu không khớp.',
        ]);

        $role = $request->role;
        
        // Nếu là giáo viên, tạo tài khoản với trạng thái Pending
        if ($role === 'Teacher') {
            NguoiDung::create([
                'HoTen'         => $request->hoTen,
                'TenDangNhap'   => $request->tenDangNhap,
                'Email'         => $request->email,
                'NgaySinh'      => $request->ngaySinh,
                'MatKhau'       => Hash::make($request->matKhau),
                'Role'          => 'Teacher',
                'TrangThai'     => 'Active',
                'TeacherStatus' => 'Pending',
            ]);

            return redirect()->route('login')
                             ->with('success', 'Đăng ký thành công! Tài khoản giáo viên của bạn sẽ được duyệt bởi Admin trong thời gian sớm nhất.');
        } else {
            // Nếu là học sinh, tạo tài khoản và có thể dùng ngay
            NguoiDung::create([
                'HoTen'       => $request->hoTen,
                'TenDangNhap' => $request->tenDangNhap,
                'Email'       => $request->email,
                'NgaySinh'    => $request->ngaySinh,
                'MatKhau'     => Hash::make($request->matKhau),
                'Role'        => 'Student',
                'TrangThai'   => 'Active',
            ]);

            return redirect()->route('login')
                             ->with('success', 'Đăng ký thành công! Hãy đăng nhập.');
        }
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
