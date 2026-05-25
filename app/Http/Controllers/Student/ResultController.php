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

    $baiLam = DB::table('BaiLamCuaHS as bl')
        ->join('DeThi as dt', 'dt.MaDeThi', '=', 'bl.MaDeThi')
        ->where('bl.MaBaiLam', $maBaiLam)
        ->where('bl.MaNguoiDung', $maNguoiDung)
        ->select('bl.*', 'dt.TenDeThi', 'dt.ThoiGian', 'dt.SoCauHoi')
        ->first();

    if (!$baiLam) abort(403, 'Bạn không có quyền xem bài làm này.');

    // ── Phần I ──────────────────────────────────────────────
    $ketQuaPhan1 = DB::table('CauHoiTrongDe as chtd')
        ->join('Question as q', 'q.MaCauHoi', '=', 'chtd.MaCauHoi')
        ->leftJoin('ChiTietTraLoiTN as ct', function($join) use ($maBaiLam) {
            $join->on('ct.MaCauHoi', '=', 'chtd.MaCauHoi')
                 ->where('ct.MaBaiLam', '=', $maBaiLam);
        })
        ->leftJoin('DapAnTN as da', 'da.MaDATN', '=', 'ct.MaDATN')
        ->where('chtd.MaDeThi', $baiLam->MaDeThi)
        ->where('chtd.Phan', 'I')
        ->orderBy('chtd.ThuTu')
        ->select(
            'q.MaCauHoi', 'q.NoiDungCH', 'q.GiaiThich', 'q.HinhAnh',
            'da.KyHieu as DaChon', 'da.NoiDungDapAn as NoiDungDaChon',
            'ct.DungSai', 'ct.DiemDatDuoc', 'chtd.ThuTu'
        )
        ->get();

    foreach ($ketQuaPhan1 as $cau) {
        $cau->dapAnDung  = DB::table('DapAnTN')->where('MaCauHoi', $cau->MaCauHoi)->where('LaDapAnDung', 1)->first();
        $cau->tatCaDapAn = DB::table('DapAnTN')->where('MaCauHoi', $cau->MaCauHoi)->get();
    }

    // ── Phần II ─────────────────────────────────────────────
    $ketQuaPhan2 = DB::table('CauHoiTrongDe as chtd')
        ->join('Question as q', 'q.MaCauHoi', '=', 'chtd.MaCauHoi')
        ->where('chtd.MaDeThi', $baiLam->MaDeThi)
        ->where('chtd.Phan', 'II')
        ->orderBy('chtd.ThuTu')
        ->select('q.MaCauHoi', 'q.NoiDungCH', 'q.GiaiThich', 'q.HinhAnh', 'chtd.ThuTu')
        ->get();

    foreach ($ketQuaPhan2 as $cau) {
        $cau->cacY = DB::table('CauHoiDS_Y as y')
            ->leftJoin('ChiTietTraLoiDS as ct', function($join) use ($maBaiLam) {
                $join->on('ct.MaY', '=', 'y.MaY')
                     ->where('ct.MaBaiLam', '=', $maBaiLam);
            })
            ->where('y.MaCauHoi', $cau->MaCauHoi)
            ->orderBy('y.KyHieu')
            ->select('y.KyHieu', 'y.DapAnDung', 'ct.LuaChonCuaHocSinh', 'ct.DungSai')
            ->get();

        $soYDung = collect($cau->cacY)->filter(fn($y) => $y->DungSai == 1)->count();
        $cau->diemDat = DB::table('ThangDiemDS')->where('SoYDung', $soYDung)->value('DiemDat') ?? 0;
    }

    // ── Phần III ────────────────────────────────────────────
    $ketQuaPhan3 = DB::table('CauHoiTrongDe as chtd')
        ->join('Question as q', 'q.MaCauHoi', '=', 'chtd.MaCauHoi')
        ->leftJoin('ChiTietCauTraLoiSo as ct', function($join) use ($maBaiLam) {
            $join->on('ct.MaCauHoi', '=', 'chtd.MaCauHoi')
                 ->where('ct.MaBaiLam', '=', $maBaiLam);
        })
        ->leftJoin('DapAnTLS as da', 'da.MaCauHoi', '=', 'chtd.MaCauHoi')
        ->where('chtd.MaDeThi', $baiLam->MaDeThi)
        ->where('chtd.Phan', 'III')
        ->orderBy('chtd.ThuTu')
        ->select(
            'q.MaCauHoi', 'q.NoiDungCH', 'q.GiaiThich', 'q.HinhAnh',
            'ct.CauTraLoiSo', 'ct.DungSai', 'ct.DiemDatDuoc',
            'da.DapAnSo', 'chtd.ThuTu'
        )
        ->get();

    return view('student.results.show', compact(
        'baiLam', 'ketQuaPhan1', 'ketQuaPhan2', 'ketQuaPhan3'
    ));
}
}