<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Hiển thị dashboard admin
     */
    public function dashboard()
    {
        // Đếm các số liệu thống kê
        $totalUsers = NguoiDung::count();
        $studentCount = NguoiDung::where('Role', 'Student')->count();
        $teacherCount = NguoiDung::where('Role', 'Teacher')->count();
        $pendingTeachers = NguoiDung::where('Role', 'Teacher')
                                    ->where('TeacherStatus', 'Pending')
                                    ->count();
        $blockedUsers = NguoiDung::where('TrangThai', 'Banned')->count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            'pendingTeachers' => $pendingTeachers,
            'blockedUsers' => $blockedUsers,
        ]);
    }

    /**
     * Danh sách giáo viên chờ duyệt
     */
    public function pendingTeachers()
    {
        $teachers = NguoiDung::where('Role', 'Teacher')
                            ->where('TeacherStatus', 'Pending')
                            ->paginate(10);

        return view('admin.teachers.pending', [
            'teachers' => $teachers
        ]);
    }

    /**
     * Duyệt tài khoản giáo viên
     */
    public function approveTeacher($id)
    {
        $teacher = NguoiDung::findOrFail($id);

        if ($teacher->Role !== 'Teacher') {
            return back()->with('error', 'Chỉ có thể duyệt tài khoản giáo viên.');
        }

        $teacher->TeacherStatus = 'Approved';
        $teacher->save();

        return back()->with('success', "Đã duyệt tài khoản giáo viên: {$teacher->HoTen}");

    }

    /**
     * Từ chối tài khoản giáo viên
     */
    public function rejectTeacher($id)
    {
        $teacher = NguoiDung::findOrFail($id);

        if ($teacher->Role !== 'Teacher') {
            return back()->with('error', 'Chỉ có thể từ chối tài khoản giáo viên.');
        }

        if ($teacher->TeacherStatus !== 'Pending') {
            return back()->with('error', 'Chỉ có thể từ chối tài khoản giáo viên đang chờ duyệt.');
        }

        $teacherName = $teacher->HoTen;
        $teacher->delete();

        return back()->with('success', "Đã từ chối và xóa tài khoản giáo viên: {$teacherName}");

    }

    /**
     * Danh sách tất cả người dùng
     */
    public function users()
    {
        $search = request('search');
        $role = request('role');
        $status = request('status');

        $query = NguoiDung::query();

        if ($search) {
            $query->where('HoTen', 'like', "%{$search}%")
                  ->orWhere('TenDangNhap', 'like', "%{$search}%")
                  ->orWhere('Email', 'like', "%{$search}%");
        }

        if ($role && $role !== 'all') {
            $query->where('Role', $role);
        }

        if ($status && $status !== 'all') {
            $query->where('TrangThai', $status);
        }

        $users = $query->paginate(15);

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'status' => $status,
        ]);
    }

    public function blockUser($id)
    {
        $user = NguoiDung::findOrFail($id);

        if ($user->Role === 'Admin') {
            return back()->with('error', 'Không thể chặn tài khoản Admin.');
        }

        $user->TrangThai = 'Banned';
        $user->save();

        return back()->with('success', "Đã chặn tài khoản: {$user->HoTen}");
    }

    /**
     * Bỏ chặn người dùng
     */
    public function unblockUser($id)
    {
        $user = NguoiDung::findOrFail($id);

        $user->TrangThai = 'Active';
        $user->save();

        return back()->with('success', "Đã bỏ chặn tài khoản: {$user->HoTen}");
    }

    /**
     * Xóa người dùng
     */
    public function deleteUser($id)
    {
        $user = NguoiDung::findOrFail($id);

        if ($user->Role === 'Admin') {
            return back()->with('error', 'Không thể xóa tài khoản Admin.');
        }

        $userName = $user->HoTen;
        $user->delete();

        return back()->with('success', "Đã xóa tài khoản: {$userName}");
    }

    /**
     * Chi tiết người dùng
     */
    public function userDetail($id)
    {
        $user = NguoiDung::findOrFail($id);

        return view('admin.users.detail', [
            'user' => $user
        ]);
    }
}
