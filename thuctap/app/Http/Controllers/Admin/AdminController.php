<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Hiển thị dashboard admin
     */
    public function dashboard()
    {
        // Đếm các số liệu thống kê
        $totalUsers = $this->manageableUsersQuery()->count();
        $studentCount = NguoiDung::where('Role', 'Student')->count();
        $teacherCount = NguoiDung::where('Role', 'Teacher')
                                ->where('TeacherStatus', 'Approved')
                                ->count();
        $pendingTeachers = $this->pendingTeachersQuery()->count();
        $blockedUsers = $this->manageableUsersQuery()
                            ->where('TrangThai', 'Banned')
                            ->count();

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
        $teachers = $this->pendingTeachersQuery()
                            ->latest('created_at')
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

        if (! in_array($teacher->TeacherStatus, ['Pending', null], true)) {
            return back()->with('error', 'Chi co the duyet tai khoan giao vien dang cho duyet.');
        }

        $teacher->TeacherStatus = 'Approved';
        $teacher->TrangThai = 'Active';
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

        if (! in_array($teacher->TeacherStatus, ['Pending', null], true)) {
            return back()->with('error', 'Chỉ có thể từ chối tài khoản giáo viên đang chờ duyệt.');
        }

        $teacherName = $teacher->HoTen;
        DB::transaction(function () use ($teacher) {
            $this->deleteUserWithRelatedData($teacher);
        });

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

        $query = $this->manageableUsersQuery();

        if ($search) {
            $query->where(function (Builder $query) use ($search) {
                $query->where('HoTen', 'like', "%{$search}%")
                      ->orWhere('TenDangNhap', 'like', "%{$search}%")
                      ->orWhere('Email', 'like', "%{$search}%");
            });
        }

        if ($role && $role !== 'all') {
            $query->where('Role', $role);
        }

        if ($status && $status !== 'all') {
            $query->where('TrangThai', $status);
        }

        $users = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'status' => $status,
        ]);
    }

    public function blockUser($id)
    {
        $user = $this->manageableUsersQuery()->findOrFail($id);

        if ($user->Role === 'Admin') {
            return back()->with('error', 'Không thể chặn tài khoản Admin.');
        }

        if ($user->Role === 'Teacher' && $user->TeacherStatus !== 'Approved') {
            return back()->with('error', 'Giao vien chua duyet khong nam trong danh sach tai khoan dang hoat dong.');
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
        $user = $this->manageableUsersQuery()->findOrFail($id);

        if ($user->Role === 'Teacher' && $user->TeacherStatus !== 'Approved') {
            return back()->with('error', 'Giao vien chua duyet khong nam trong danh sach tai khoan dang hoat dong.');
        }

        $user->TrangThai = 'Active';
        $user->save();

        return back()->with('success', "Đã bỏ chặn tài khoản: {$user->HoTen}");
    }

    /**
     * Xóa người dùng
     */
    public function deleteUser($id)
    {
        $user = $this->manageableUsersQuery()->findOrFail($id);

        if ($user->Role === 'Admin') {
            return back()->with('error', 'Không thể xóa tài khoản Admin.');
        }

        $userName = $user->HoTen;
        $summary = DB::transaction(function () use ($user) {
            return $this->deleteUserWithRelatedData($user);
        });

        return back()->with(
            'success',
            "Đã xóa tài khoản: {$userName}. Dữ liệu đã xóa kèm: " . implode(', ', $summary)
        );
    }

    /**
     * Chi tiết người dùng
     */
    public function userDetail($id)
    {
        $user = $this->manageableUsersQuery()->findOrFail($id);

        return view('admin.users.detail', [
            'user' => $user
        ]);
    }

    private function manageableUsersQuery(): Builder
    {
        return NguoiDung::query()
            ->where(function (Builder $query) {
                $query->where('Role', 'Student')
                      ->orWhere(function (Builder $query) {
                          $query->where('Role', 'Teacher')
                                ->where('TeacherStatus', 'Approved');
                      });
            });
    }

    private function pendingTeachersQuery(): Builder
    {
        return NguoiDung::query()
            ->where('Role', 'Teacher')
            ->where(function (Builder $query) {
                $query->where('TeacherStatus', 'Pending')
                      ->orWhereNull('TeacherStatus');
            });
    }

    private function deleteUserWithRelatedData(NguoiDung $user): array
    {
        $userId = $user->MaNguoiDung;

        if ($user->Role === 'Student') {
            $baiLamIds = DB::table('BaiLamCuaHS')
                ->where('MaNguoiDung', $userId)
                ->pluck('MaBaiLam')
                ->all();

            $summary = [count($baiLamIds) . ' bài làm'];
            $this->deleteBaiLamCascade($baiLamIds);
            $this->deleteCommentsCascade(userId: $userId);
            $user->delete();

            return $summary;
        }

        if ($user->Role === 'Teacher') {
            $questionIds = DB::table('Question')
                ->where('MaNguoiTao', $userId)
                ->pluck('MaCauHoi')
                ->all();

            $examIds = DB::table('DeThi')
                ->where('MaNguoiTaoDe', $userId)
                ->pluck('MaDeThi')
                ->all();

            if ($questionIds !== []) {
                $examIdsUsingTeacherQuestions = DB::table('CauHoiTrongDe')
                    ->whereIn('MaCauHoi', $questionIds)
                    ->pluck('MaDeThi')
                    ->all();

                $examIds = array_values(array_unique(array_merge($examIds, $examIdsUsingTeacherQuestions)));
            }

            $summary = [
                count($examIds) . ' đề thi',
                count($questionIds) . ' câu hỏi',
            ];

            $this->deleteExamsCascade($examIds);
            $this->deleteQuestionsCascade($questionIds);
            $this->deleteCommentsCascade(userId: $userId);
            $user->delete();

            return $summary;
        }

        $this->deleteCommentsCascade(userId: $userId);
        $user->delete();

        return ['0 dữ liệu liên quan'];
    }

    private function deleteExamsCascade(array $examIds): void
    {
        if ($examIds === []) {
            return;
        }

        $baiLamIds = DB::table('BaiLamCuaHS')
            ->whereIn('MaDeThi', $examIds)
            ->pluck('MaBaiLam')
            ->all();

        $this->deleteBaiLamCascade($baiLamIds);

        DB::table('CauHoiTrongDe')->whereIn('MaDeThi', $examIds)->delete();
        DB::table('DeThi')->whereIn('MaDeThi', $examIds)->delete();
    }

    private function deleteBaiLamCascade(array $baiLamIds): void
    {
        if ($baiLamIds === []) {
            return;
        }

        $this->deleteCommentsCascade(baiLamIds: $baiLamIds);

        DB::table('ChiTietTraLoiTN')->whereIn('MaBaiLam', $baiLamIds)->delete();
        DB::table('ChiTietTraLoiDS')->whereIn('MaBaiLam', $baiLamIds)->delete();
        DB::table('ChiTietCauTraLoiSo')->whereIn('MaBaiLam', $baiLamIds)->delete();
        DB::table('BaiLamCuaHS')->whereIn('MaBaiLam', $baiLamIds)->delete();
    }

    private function deleteQuestionsCascade(array $questionIds): void
    {
        if ($questionIds === []) {
            return;
        }

        $this->deleteCommentsCascade(questionIds: $questionIds);

        DB::table('ChiTietTraLoiTN')->whereIn('MaCauHoi', $questionIds)->delete();
        DB::table('ChiTietTraLoiDS')->whereIn('MaCauHoi', $questionIds)->delete();
        DB::table('ChiTietCauTraLoiSo')->whereIn('MaCauHoi', $questionIds)->delete();
        DB::table('CauHoiTrongDe')->whereIn('MaCauHoi', $questionIds)->delete();

        DB::table('DapAnTN')->whereIn('MaCauHoi', $questionIds)->delete();
        DB::table('DapAnTLS')->whereIn('MaCauHoi', $questionIds)->delete();
        DB::table('CauHoiDS_Y')->whereIn('MaCauHoi', $questionIds)->delete();
        DB::table('Question')->whereIn('MaCauHoi', $questionIds)->delete();
    }

    private function deleteCommentsCascade(
        ?int $userId = null,
        array $baiLamIds = [],
        array $questionIds = [],
    ): void {
        $commentIds = DB::table('BinhLuan')
            ->when($userId !== null, fn ($query) => $query->orWhere('MaNguoiDung', $userId))
            ->when($baiLamIds !== [], fn ($query) => $query->orWhereIn('MaBaiLam', $baiLamIds))
            ->when($questionIds !== [], fn ($query) => $query->orWhereIn('MaCauHoi', $questionIds))
            ->pluck('MaBinhLuan')
            ->all();

        if ($commentIds === []) {
            return;
        }

        do {
            $childIds = DB::table('BinhLuan')
                ->whereIn('MaBinhLuanCha', $commentIds)
                ->whereNotIn('MaBinhLuan', $commentIds)
                ->pluck('MaBinhLuan')
                ->all();

            $commentIds = array_values(array_unique(array_merge($commentIds, $childIds)));
        } while ($childIds !== []);

        DB::table('BinhLuan')
            ->whereIn('MaBinhLuanCha', $commentIds)
            ->update(['MaBinhLuanCha' => null]);

        rsort($commentIds);

        DB::table('BinhLuan')
            ->whereIn('MaBinhLuan', $commentIds)
            ->delete();
    }

}
