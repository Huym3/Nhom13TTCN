<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ExamController extends Controller
{
    // ── Danh sách đề thi của giáo viên ──────────────────────
    public function index()
    {
        $exams = DB::table('DeThi as de')
            ->where('de.MaNguoiTaoDe', Session::get('maNguoiDung'))
            ->orderBy('de.NgayTao', 'desc')
            ->select(
                'de.*',
                DB::raw('(SELECT COUNT(*) FROM CauHoiTrongDe WHERE MaDeThi = de.MaDeThi) as SoCauThucTe'),
                DB::raw('(SELECT COUNT(*) FROM BaiLamCuaHS WHERE MaDeThi = de.MaDeThi) as SoBaiLam')
            )
            ->get();

        return view('teacher.exams.index', compact('exams'));
    }

    // ── Form tạo đề thi mới ──────────────────────────────────
    public function create()
    {
        return view('teacher.exams.create');
    }

    // ── Lưu đề thi mới ──────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'tenDeThi'  => 'required|string|max:100',
            'thoiGian'  => 'required|in:15,45,60,90',
        ], [
            'tenDeThi.required' => 'Vui lòng nhập tên đề thi.',
            'thoiGian.required' => 'Vui lòng chọn thời gian làm bài.',
        ]);

        DB::table('DeThi')->insert([
            'TenDeThi'     => $request->tenDeThi,
            'MaDe'         => $request->maDe,
            'ThoiGian'     => $request->thoiGian,
            'CauTrucDe'    => $request->cauTruc,
            'MaNguoiTaoDe' => Session::get('maNguoiDung'),
            'TrangThaiDe'  => 'Draft',
            'NgayTao'      => now(),
        ]);

        return redirect()->route('teacher.exams.index')
            ->with('success', 'Tạo đề thi thành công!');
    }

    // ── Form sửa đề thi ──────────────────────────────────────
    public function edit($id)
    {
        $exam = DB::table('DeThi')->where('MaDeThi', $id)
            ->where('MaNguoiTaoDe', Session::get('maNguoiDung'))
            ->first();

        if (!$exam) abort(404);
        if ($exam->TrangThaiDe === 'Published') {
            return redirect()->route('teacher.exams.index')
                ->with('error', 'Không thể sửa đề đã Published.');
        }

        // Câu hỏi đã có trong đề
        $cauHoiTrongDe = DB::table('CauHoiTrongDe as ctd')
            ->join('Question as q', 'q.MaCauHoi', '=', 'ctd.MaCauHoi')
            ->join('ChuyenDe as cd', 'cd.MaChuyenDe', '=', 'q.MaChuyenDe')
            ->where('ctd.MaDeThi', $id)
            ->orderBy('ctd.Phan')
            ->orderBy('ctd.ThuTu')
            ->select('ctd.*', 'q.NoiDungCH', 'q.LoaiCauHoi', 'q.DoKho', 'cd.TenChuyenDe')
            ->select('ctd.*', 'q.NoiDungCH', 'q.HinhAnh', 'q.LoaiCauHoi', 'q.DoKho', 'cd.TenChuyenDe') // Thêm HinhAnh
            ->get();

        // Câu hỏi của giáo viên chưa có trong đề
        $cauHoiCoThe = DB::table('Question as q')
            ->join('ChuyenDe as cd', 'cd.MaChuyenDe', '=', 'q.MaChuyenDe')
            ->where('q.MaNguoiTao', Session::get('maNguoiDung'))
            ->whereNotIn('q.MaCauHoi', $cauHoiTrongDe->pluck('MaCauHoi'))
            ->select('q.*', 'cd.TenChuyenDe')
            ->orderBy('q.LoaiCauHoi')
            ->orderBy('q.NgayTao', 'desc')
            ->get();

        return view('teacher.exams.edit', compact('exam', 'cauHoiTrongDe', 'cauHoiCoThe'));
    }

    // ── Cập nhật thông tin đề ────────────────────────────────
    public function update(Request $request, $id)
    {
        $exam = DB::table('DeThi')->where('MaDeThi', $id)
            ->where('MaNguoiTaoDe', Session::get('maNguoiDung'))
            ->first();

        if (!$exam) abort(404);

        DB::table('DeThi')->where('MaDeThi', $id)->update([
            'TenDeThi'   => $request->tenDeThi,
            'MaDe'       => $request->maDe,
            'ThoiGian'   => $request->thoiGian,
            'CauTrucDe'  => $request->cauTruc,
            'NgayCapNhat'=> now(),
        ]);

        return redirect()->route('teacher.exams.edit', $id)
            ->with('success', 'Cập nhật đề thi thành công!');
    }

    // ── Xóa đề thi ──────────────────────────────────────────
    public function destroy($id)
{
    $exam = DB::table('DeThi')->where('MaDeThi', $id)
        ->where('MaNguoiTaoDe', Session::get('maNguoiDung'))
        ->first();

    if (!$exam) abort(404);

    $soBaiLam = DB::table('BaiLamCuaHS')->where('MaDeThi', $id)->count();

    // Nếu có bài làm và chưa xác nhận → trả về cảnh báo
    if ($soBaiLam > 0 && !request('confirmed')) {
        return redirect()->back()
            ->with('confirm_delete', $id)
            ->with('so_bai_lam', $soBaiLam)
            ->with('ten_de_thi', $exam->TenDeThi);
    }

    // Xóa toàn bộ dữ liệu liên quan
    $maBaiLams = DB::table('BaiLamCuaHS')->where('MaDeThi', $id)->pluck('MaBaiLam');

    if ($maBaiLams->isNotEmpty()) {
        // Xóa chi tiết trả lời TN
        $maCauTiets = DB::table('ChiTietTraLoiTN')
            ->whereIn('MaBaiLam', $maBaiLams)->pluck('MaChiTietTN');
        DB::table('ChiTietTraLoiTN')->whereIn('MaBaiLam', $maBaiLams)->delete();

        // Xóa chi tiết trả lời DS
        DB::table('ChiTietTraLoiDS')->whereIn('MaBaiLam', $maBaiLams)->delete();

        // Xóa chi tiết trả lời số
        DB::table('ChiTietCauTraLoiSo')->whereIn('MaBaiLam', $maBaiLams)->delete();

        // Xóa bình luận
        DB::table('BinhLuan')->whereIn('MaBaiLam', $maBaiLams)->delete();

        // Xóa bài làm
        DB::table('BaiLamCuaHS')->where('MaDeThi', $id)->delete();
    }

    DB::table('CauHoiTrongDe')->where('MaDeThi', $id)->delete();
    DB::table('DeThi')->where('MaDeThi', $id)->delete();

    return redirect()->route('teacher.exams.index')
        ->with('success', 'Đã xóa đề thi và toàn bộ dữ liệu liên quan.');
}
    // ── Thêm câu hỏi vào đề ─────────────────────────────────
    public function addQuestion(Request $request, $id)
{
    // Thêm 2 dòng này vào đầu hàm
    $maCauHoi = $request->input('maCauHoi');
    $phan     = $request->input('phan');

    // Sửa validate dùng biến thay vì $request trực tiếp
    if (!$maCauHoi || !$phan) {
        return response()->json(['error' => 'Thiếu dữ liệu'], 400);
    }

    $exam = DB::table('DeThi')->where('MaDeThi', $id)->first();
    if (!$exam) abort(404);

    $question = DB::table('Question')->where('MaCauHoi', $maCauHoi)->first();
    if (!$question) abort(404);

    $loaiHopLe = ['I' => 'TN', 'II' => 'DS', 'III' => 'TLS'];

    if ($loaiHopLe[$phan] !== $question->LoaiCauHoi) {
        return response()->json(['error' => 'Loại câu hỏi không khớp'], 422);
    }

    $daCoTrongDe = DB::table('CauHoiTrongDe')
        ->where('MaCauHoi', $maCauHoi)
        ->where('MaDeThi', $id)
        ->exists();

    if ($daCoTrongDe) {
        return response()->json(['error' => 'Câu hỏi đã có trong đề'], 422);
    }

    $diemTheoPhan = ['I' => 0.25, 'II' => 1.00, 'III' => 0.50];

    $thuTu = DB::table('CauHoiTrongDe')
        ->where('MaDeThi', $id)
        ->where('Phan', $phan)
        ->max('ThuTu') + 1;

    DB::table('CauHoiTrongDe')->insert([
        'MaCauHoi'   => $maCauHoi,
        'MaDeThi'    => $id,
        'Phan'       => $phan,
        'ThuTu'      => $thuTu,
        'DiemCauHoi' => $diemTheoPhan[$phan],
    ]);

    $tong = DB::table('CauHoiTrongDe')->where('MaDeThi', $id)->count();
    DB::table('DeThi')->where('MaDeThi', $id)->update([
        'SoCauHoi'    => $tong,
        'NgayCapNhat' => now(),
    ]);

    return response()->json(['ok' => true]);
}
    // ── Xóa câu hỏi khỏi đề ─────────────────────────────────
    public function removeQuestion(Request $request, $id)
    {
        DB::table('CauHoiTrongDe')
            ->where('MaDeThi', $id)
            ->where('MaCauHoi', $request->maCauHoi)
            ->delete();

        $tong = DB::table('CauHoiTrongDe')->where('MaDeThi', $id)->count();
        DB::table('DeThi')->where('MaDeThi', $id)->update([
            'SoCauHoi'   => $tong,
            'NgayCapNhat'=> now(),
        ]);

        return back()->with('success', 'Đã xóa câu hỏi khỏi đề.');
    }

    // ── Publish đề (Draft → Published) ──────────────────────
    public function publish($id)
    {
        $exam = DB::table('DeThi')->where('MaDeThi', $id)
            ->where('MaNguoiTaoDe', Session::get('maNguoiDung'))
            ->first();

        if (!$exam) abort(404);

        // Kiểm tra phải có ít nhất 1 câu hỏi
        $soCau = DB::table('CauHoiTrongDe')->where('MaDeThi', $id)->count();
        if ($soCau === 0) {
            return redirect()->route('teacher.exams.index')
                ->with('error', 'Đề thi chưa có câu hỏi nào.');
        }

        DB::table('DeThi')->where('MaDeThi', $id)->update([
            'TrangThaiDe' => 'Published',
            'NgayCapNhat' => now(),
        ]);

        return redirect()->route('teacher.exams.index')
            ->with('success', 'Đã publish đề thi thành công!');

    }

// ── Unpublish đề (Published → Draft) ────────────────────
public function unpublish($id)
{
    $exam = DB::table('DeThi')->where('MaDeThi', $id)
        ->where('MaNguoiTaoDe', Session::get('maNguoiDung'))
        ->first();

    if (!$exam) abort(404);

    $soBaiLam = DB::table('BaiLamCuaHS')->where('MaDeThi', $id)->count();

    // Nếu có bài làm và chưa xác nhận
    if ($soBaiLam > 0 && !request('confirmed')) {
        return redirect()->back()
            ->with('confirm_unpublish', $id)
            ->with('so_bai_lam_unpublish', $soBaiLam)
            ->with('ten_de_thi_unpublish', $exam->TenDeThi);
    }

    // Xóa toàn bộ dữ liệu bài làm
    $maBaiLams = DB::table('BaiLamCuaHS')->where('MaDeThi', $id)->pluck('MaBaiLam');

    if ($maBaiLams->isNotEmpty()) {
        DB::table('ChiTietTraLoiTN')->whereIn('MaBaiLam', $maBaiLams)->delete();
        DB::table('ChiTietTraLoiDS')->whereIn('MaBaiLam', $maBaiLams)->delete();
        DB::table('ChiTietCauTraLoiSo')->whereIn('MaBaiLam', $maBaiLams)->delete();
        DB::table('BinhLuan')->whereIn('MaBaiLam', $maBaiLams)->delete();
        DB::table('BaiLamCuaHS')->where('MaDeThi', $id)->delete();
    }

    DB::table('DeThi')->where('MaDeThi', $id)->update([
        'TrangThaiDe' => 'Draft',
        'NgayCapNhat' => now(),
    ]);

    return redirect()->route('teacher.exams.index')
        ->with('success', 'Đã chuyển về nháp và xóa toàn bộ bài làm của học sinh.');
}

    // ── Thống kê đề thi ──────────────────────────────────────
    public function stats($id)
    {
        $exam = DB::table('DeThi')->where('MaDeThi', $id)
            ->where('MaNguoiTaoDe', Session::get('maNguoiDung'))
            ->first();

        if (!$exam) abort(404);

        // Tổng quan bài làm
        $tongQuan = DB::table('BaiLamCuaHS')
            ->where('MaDeThi', $id)
            ->whereNotNull('ThoiGianNopBai')
            ->selectRaw('
                COUNT(*) as TongBaiLam,
                AVG(TongDiem) as DiemTrungBinh,
                MAX(TongDiem) as DiemCaoNhat,
                MIN(TongDiem) as DiemThapNhat,
                AVG(TongThoiGianLamBai) as ThoiGianTB
            ')
            ->first();

        // Phân bố điểm
       // Thay vì query từ BaiLamCuaHS, tạo đủ 6 khoảng điểm cố định
$phanBoRaw = DB::table('BaiLamCuaHS')
    ->where('MaDeThi', $id)
    ->whereNotNull('ThoiGianNopBai')
    ->selectRaw("
        CASE
            WHEN TongDiem < 2   THEN '0 - 2'
            WHEN TongDiem < 4   THEN '2 - 4'
            WHEN TongDiem < 5   THEN '4 - 5'
            WHEN TongDiem < 6.5 THEN '5 - 6.5'
            WHEN TongDiem < 8   THEN '6.5 - 8'
            ELSE '8 - 10'
        END as KhoangDiem,
        COUNT(*) as SoLuong
    ")
    ->groupByRaw("KhoangDiem")
    ->pluck('SoLuong', 'KhoangDiem');

// Đảm bảo đủ 6 khoảng, khoảng không có thì = 0
$tatCaKhoang = ['0 - 2', '2 - 4', '4 - 5', '5 - 6.5', '6.5 - 8', '8 - 10'];
$phanBoDiem = collect($tatCaKhoang)->map(fn($k) => (object)[
    'KhoangDiem' => $k,
    'SoLuong'    => $phanBoRaw[$k] ?? 0,
]);

        // Top 5 học sinh điểm cao
        $topHocSinh = DB::table('BaiLamCuaHS as bl')
            ->join('NguoiDung as nd', 'nd.MaNguoiDung', '=', 'bl.MaNguoiDung')
            ->where('bl.MaDeThi', $id)
            ->whereNotNull('bl.ThoiGianNopBai')
            ->orderByDesc('bl.TongDiem')
            ->orderBy('bl.TongThoiGianLamBai')
            ->limit(5)
            ->select('nd.HoTen', 'bl.MaBaiLam', 'bl.TongDiem', 'bl.SoCauDung', 'bl.TongThoiGianLamBai', 'bl.ThoiGianNopBai')
            ->get();

        // Tỉ lệ đúng từng câu hỏi (Phần I)
// Tỉ lệ đúng từng câu hỏi (Phần I)
$tiLeUngCauTN = DB::table('CauHoiTrongDe as ctd')
    ->join('Question as q', 'q.MaCauHoi', '=', 'ctd.MaCauHoi')
    ->leftJoin('ChiTietTraLoiTN as ct', function($join) use ($id) {
        $join->on('ct.MaCauHoi', '=', 'ctd.MaCauHoi')
             ->whereExists(function($sub) use ($id) {
                 $sub->select(DB::raw(1))
                     ->from('BaiLamCuaHS')
                     ->whereColumn('BaiLamCuaHS.MaBaiLam', 'ct.MaBaiLam')
                     ->where('BaiLamCuaHS.MaDeThi', $id);
             });
    })
    ->where('ctd.MaDeThi', $id)
    ->where('ctd.Phan', 'I')
    ->orderBy('ctd.ThuTu')
    ->groupBy('ctd.MaCauHoi', 'q.NoiDungCH', 'q.HinhAnh', 'ctd.ThuTu')
   ->selectRaw('ctd.ThuTu, LEFT(q.NoiDungCH, 60) as NoiDung,
    q.HinhAnh,
    COUNT(ct.MaBaiLam) as TongTraLoi,
    SUM(ct.DungSai) as SoDung')
    ->get();


                // Tỉ lệ đúng từng ý — Phần II (Đúng/Sai)
        $tiLeUngCauDS = DB::table('CauHoiTrongDe as ctd')
            ->join('Question as q', 'q.MaCauHoi', '=', 'ctd.MaCauHoi')
            ->join('CauHoiDS_Y as y', 'y.MaCauHoi', '=', 'ctd.MaCauHoi')
            ->leftJoin('ChiTietTraLoiDS as ct', function($join) use ($id) {
                $join->on('ct.MaY', '=', 'y.MaY')
                    ->whereExists(function($sub) use ($id) {
                        $sub->select(DB::raw(1))
                            ->from('BaiLamCuaHS')
                            ->whereColumn('BaiLamCuaHS.MaBaiLam', 'ct.MaBaiLam')
                            ->where('BaiLamCuaHS.MaDeThi', $id);
                    });
            })
            ->where('ctd.MaDeThi', $id)
            ->where('ctd.Phan', 'II')
            ->orderBy('ctd.ThuTu')
            ->orderBy('y.KyHieu')
            ->groupBy('ctd.MaCauHoi', 'ctd.ThuTu', 'q.HinhAnh', 'q.NoiDungCH', 'y.MaY', 'y.KyHieu', 'y.DapAnDung')
           ->selectRaw('ctd.ThuTu, q.HinhAnh, LEFT(q.NoiDungCH, 60) as NoiDung,
    y.MaY, y.KyHieu, y.DapAnDung,
    COUNT(ct.MaBaiLam) as TongTraLoi,
    SUM(CASE WHEN ct.LuaChonCuaHocSinh = y.DapAnDung THEN 1 ELSE 0 END) as SoDung')
            ->get();

        // Tỉ lệ đúng — Phần III (Trả lời số)
$tiLeUngCauTLS = DB::table('CauHoiTrongDe as ctd')
    ->join('Question as q', 'q.MaCauHoi', '=', 'ctd.MaCauHoi')
    ->join('DapAnTLS as da', 'da.MaCauHoi', '=', 'ctd.MaCauHoi')
    ->leftJoin('ChiTietCauTraLoiSo as ct', function($join) use ($id) {
        $join->on('ct.MaCauHoi', '=', 'ctd.MaCauHoi')
             ->whereExists(function($sub) use ($id) {
                 $sub->select(DB::raw(1))
                     ->from('BaiLamCuaHS')
                     ->whereColumn('BaiLamCuaHS.MaBaiLam', 'ct.MaBaiLam')
                     ->where('BaiLamCuaHS.MaDeThi', $id);
             });
    })
    ->where('ctd.MaDeThi', $id)
    ->where('ctd.Phan', 'III')
    ->orderBy('ctd.ThuTu')
    ->groupBy('ctd.MaCauHoi', 'ctd.ThuTu', 'q.HinhAnh', 'q.NoiDungCH', 'da.DapAnSo', 'da.SaiSoChapNhan')
   ->selectRaw('ctd.ThuTu, q.HinhAnh, LEFT(q.NoiDungCH, 60) as NoiDung,
    da.DapAnSo, da.SaiSoChapNhan,
    COUNT(ct.MaBaiLam) as TongTraLoi,
    SUM(CASE WHEN ABS(CAST(REPLACE(ct.CauTraLoiSo, ",", ".") AS DECIMAL(12,4)) - da.DapAnSo) <= da.SaiSoChapNhan THEN 1 ELSE 0 END) as SoDung')
    ->get();

        return view('teacher.exams.stats', compact(
            'exam', 'tongQuan', 'phanBoDiem', 'topHocSinh',
            'tiLeUngCauTN', 'tiLeUngCauDS', 'tiLeUngCauTLS'
        ));
    }

    // ── Xem chi tiết bài làm của 1 học sinh ─────────────────
public function xemBaiLam($id, $maBaiLam)
{
    $exam = DB::table('DeThi')->where('MaDeThi', $id)
        ->where('MaNguoiTaoDe', Session::get('maNguoiDung'))
        ->first();
    if (!$exam) abort(404);

    $baiLam = DB::table('BaiLamCuaHS as bl')
        ->join('NguoiDung as nd', 'nd.MaNguoiDung', '=', 'bl.MaNguoiDung')
        ->where('bl.MaBaiLam', $maBaiLam)
        ->select('bl.*', 'nd.HoTen', 'nd.TenDangNhap')
        ->first();
    if (!$baiLam) abort(404);

    // ── Phần I ──────────────────────────────────────────────
    $ketQuaPhan1 = DB::table('CauHoiTrongDe as chtd')
        ->join('Question as q', 'q.MaCauHoi', '=', 'chtd.MaCauHoi')
        ->leftJoin('ChiTietTraLoiTN as ct', function($join) use ($maBaiLam) {
            $join->on('ct.MaCauHoi', '=', 'chtd.MaCauHoi')
                 ->where('ct.MaBaiLam', '=', $maBaiLam);
        })
        ->leftJoin('DapAnTN as da', 'da.MaDATN', '=', 'ct.MaDATN')
        ->where('chtd.MaDeThi', $id)
        ->where('chtd.Phan', 'I')
        ->orderBy('chtd.ThuTu')
        ->select(
            'q.MaCauHoi', 'q.NoiDungCH', 'q.HinhAnh', 'q.GiaiThich',
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
        ->where('chtd.MaDeThi', $id)
        ->where('chtd.Phan', 'II')
        ->orderBy('chtd.ThuTu')
        ->select('q.MaCauHoi', 'q.NoiDungCH', 'q.HinhAnh', 'q.GiaiThich', 'chtd.ThuTu')
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
        ->where('chtd.MaDeThi', $id)
        ->where('chtd.Phan', 'III')
        ->orderBy('chtd.ThuTu')
        ->select(
            'q.MaCauHoi', 'q.NoiDungCH', 'q.HinhAnh', 'q.GiaiThich',
            'ct.CauTraLoiSo', 'ct.DungSai', 'ct.DiemDatDuoc',
            'da.DapAnSo', 'chtd.ThuTu'
        )
        ->get();

    return view('teacher.exams.xem-bai-lam', compact(
        'exam', 'baiLam', 'ketQuaPhan1', 'ketQuaPhan2', 'ketQuaPhan3'
    ));
}
    
}