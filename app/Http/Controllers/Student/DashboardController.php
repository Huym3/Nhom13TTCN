<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $maNguoiDung = Session::get('maNguoiDung');

        // Thống kê nhanh
        $tongBaiLam = DB::table('BaiLamCuaHS')
            ->where('MaNguoiDung', $maNguoiDung)
            ->whereNotNull('ThoiGianNopBai')
            ->count();

        $diemTrungBinh = DB::table('BaiLamCuaHS')
            ->where('MaNguoiDung', $maNguoiDung)
            ->whereNotNull('ThoiGianNopBai')
            ->avg('TongDiem');

        $diemCaoNhat = DB::table('BaiLamCuaHS')
            ->where('MaNguoiDung', $maNguoiDung)
            ->whereNotNull('ThoiGianNopBai')
            ->max('TongDiem');

        // 5 bài làm gần nhất
        $baiLamGanNhat = DB::table('BaiLamCuaHS as bl')
            ->join('DeThi as dt', 'dt.MaDeThi', '=', 'bl.MaDeThi')
            ->where('bl.MaNguoiDung', $maNguoiDung)
            ->whereNotNull('bl.ThoiGianNopBai')
            ->orderBy('bl.ThoiGianNopBai', 'desc')
            ->limit(5)
            ->select('bl.*', 'dt.TenDeThi')
            ->get();

        // Đề thi có thể làm (chưa làm)
        $daDamIds = DB::table('BaiLamCuaHS')
            ->where('MaNguoiDung', $maNguoiDung)
            ->whereNotNull('ThoiGianNopBai')
            ->pluck('MaDeThi');

        $deChuaLam = DB::table('DeThi')
            ->where('TrangThaiDe', 'Published')
            ->whereNotIn('MaDeThi', $daDamIds)
            ->orderBy('NgayTao', 'desc')
            ->limit(5)
            ->get();

        return view('student.dashboard', compact(
            'tongBaiLam', 'diemTrungBinh', 'diemCaoNhat',
            'baiLamGanNhat', 'deChuaLam'
        ));
    }
}