<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ResultController extends Controller
{
    // Lịch sử làm bài
    public function index()
    {
        $maNguoiDung = Session::get('maNguoiDung');

        $baiLam = DB::table('BaiLamCuaHS as bl')
            ->join('DeThi as dt', 'dt.MaDeThi', '=', 'bl.MaDeThi')
            ->where('bl.MaNguoiDung', $maNguoiDung)
            ->whereNotNull('bl.ThoiGianNopBai')
            ->orderBy('bl.ThoiGianNopBai', 'desc')
            ->select(
                'bl.MaBaiLam', 'bl.TongDiem', 'bl.DiemPhan1',
                'bl.DiemPhan2', 'bl.DiemPhan3', 'bl.SoCauDung',
                'bl.SoCauSai', 'bl.ThoiGianNopBai', 'bl.TongThoiGianLamBai',
                'dt.TenDeThi', 'dt.ThoiGian', 'dt.SoCauHoi'
            )
            ->get();

        return view('student.results.index', compact('baiLam'));
    }

    // Chi tiết kết quả 1 bài làm
    public function show($maBaiLam)
    {
        $maNguoiDung = Session::get('maNguoiDung');

        // Kiểm tra bài làm thuộc về học sinh này
        $baiLam = DB::table('BaiLamCuaHS as bl')
            ->join('DeThi as dt', 'dt.MaDeThi', '=', 'bl.MaDeThi')
            ->where('bl.MaBaiLam', $maBaiLam)
            ->where('bl.MaNguoiDung', $maNguoiDung)
            ->select('bl.*', 'dt.TenDeThi', 'dt.ThoiGian', 'dt.SoCauHoi')
            ->first();

        if (!$baiLam) abort(403, 'Bạn không có quyền xem bài làm này.');

        // Phần I: kết quả TN
        $ketQuaPhan1 = DB::table('ChiTietTraLoiTN as ct')
            ->join('Question as q',  'q.MaCauHoi', '=', 'ct.MaCauHoi')
            ->join('DapAnTN as da',  'da.MaDATN',  '=', 'ct.MaDATN')
            ->join('CauHoiTrongDe as chtd', function($join) use ($baiLam) {
                $join->on('chtd.MaCauHoi', '=', 'ct.MaCauHoi')
                     ->where('chtd.MaDeThi', '=', $baiLam->MaDeThi);
            })
            ->where('ct.MaBaiLam', $maBaiLam)
            ->orderBy('chtd.ThuTu')
            ->select(
                'q.MaCauHoi', 'q.NoiDungCH', 'q.GiaiThich',
                'da.KyHieu as DaChon', 'da.NoiDungDapAn as NoiDungDaChon',
                'ct.DungSai', 'ct.DiemDatDuoc', 'chtd.ThuTu'
            )
            ->get();

        // Thêm đáp án đúng cho mỗi câu Phần I
        foreach ($ketQuaPhan1 as $cau) {
            $cau->dapAnDung = DB::table('DapAnTN')
                ->where('MaCauHoi', $cau->MaCauHoi)
                ->where('LaDapAnDung', 1)
                ->first();
            // Tất cả đáp án để hiển thị
            $cau->tatCaDapAn = DB::table('DapAnTN')
                ->where('MaCauHoi', $cau->MaCauHoi)
                ->get();
        }

        // Phần II: kết quả Đúng/Sai
        $ketQuaPhan2 = DB::table('CauHoiTrongDe as chtd')
            ->join('Question as q', 'q.MaCauHoi', '=', 'chtd.MaCauHoi')
            ->where('chtd.MaDeThi', $baiLam->MaDeThi)
            ->where('chtd.Phan', 'II')
            ->orderBy('chtd.ThuTu')
            ->select('q.MaCauHoi', 'q.NoiDungCH', 'q.GiaiThich', 'chtd.ThuTu')
            ->get();

        foreach ($ketQuaPhan2 as $cau) {
            // Các ý con + lựa chọn của HS
            $cau->cacY = DB::table('CauHoiDS_Y as y')
                ->leftJoin('ChiTietTraLoiDS as ct', function($join) use ($maBaiLam) {
                    $join->on('ct.MaY', '=', 'y.MaY')
                         ->where('ct.MaBaiLam', '=', $maBaiLam);
                })
                ->where('y.MaCauHoi', $cau->MaCauHoi)
                ->orderBy('y.KyHieu')
                ->select(
                    'y.KyHieu', 'y.NoiDungY', 'y.DapAnDung',
                    'ct.LuaChonCuaHocSinh', 'ct.DungSai'
                )
                ->get();

            // Điểm đạt được của câu này
            $soYDung = collect($cau->cacY)->where('DungSai', 1)->count();
            $cau->diemDat = DB::table('ThangDiemDS')
                ->where('SoYDung', $soYDung)
                ->value('DiemDat') ?? 0;
        }

        // Phần III: kết quả số
        $ketQuaPhan3 = DB::table('ChiTietCauTraLoiSo as ct')
            ->join('Question as q', 'q.MaCauHoi', '=', 'ct.MaCauHoi')
            ->join('CauHoiTrongDe as chtd', function($join) use ($baiLam) {
                $join->on('chtd.MaCauHoi', '=', 'ct.MaCauHoi')
                     ->where('chtd.MaDeThi', '=', $baiLam->MaDeThi);
            })
            ->leftJoin('DapAnTLS as da', 'da.MaCauHoi', '=', 'ct.MaCauHoi')
            ->where('ct.MaBaiLam', $maBaiLam)
            ->orderBy('chtd.ThuTu')
            ->select(
                'q.MaCauHoi', 'q.NoiDungCH', 'q.GiaiThich',
                'ct.CauTraLoiSo', 'ct.DungSai', 'ct.DiemDatDuoc',
                'da.DapAnSo', 'chtd.ThuTu'
            )
            ->get();

        return view('student.results.show', compact(
            'baiLam', 'ketQuaPhan1', 'ketQuaPhan2', 'ketQuaPhan3'
        ));
    }
}