<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ExamController extends Controller
{
    // Danh sách đề thi đã Published
 public function index()
{
    $maNguoiDung = Session::get('maNguoiDung');

    // Lấy danh sách đề đã làm xong
    $daDamIds = DB::table('BaiLamCuaHS')
        ->where('MaNguoiDung', $maNguoiDung)
        ->whereNotNull('ThoiGianNopBai')
        ->pluck('MaDeThi');

    $deThi = DB::table('DeThi')
        ->where('TrangThaiDe', 'Published')
        ->whereNotIn('MaDeThi', $daDamIds) // ẩn đề đã làm xong
        ->orderBy('NgayTao', 'desc')
        ->get();

    return view('student.exams.index', compact('deThi'));
}
    // Bắt đầu làm bài → gọi SP tạo bài làm
    public function start($id)
{
    $maNguoiDung = Session::get('maNguoiDung');

    // Kiểm tra đã nộp bài chưa
    $daLam = DB::table('BaiLamCuaHS')
        ->where('MaDeThi', $id)
        ->where('MaNguoiDung', $maNguoiDung)
        ->whereNotNull('ThoiGianNopBai')
        ->first();

    if ($daLam) {
        return redirect()->route('student.results.show', $daLam->MaBaiLam)
            ->with('error', 'Bạn đã làm bài này rồi!');
    }

    // Kiểm tra bài làm dở dang (chưa nộp)
    $dangLam = DB::table('BaiLamCuaHS')
        ->where('MaDeThi', $id)
        ->where('MaNguoiDung', $maNguoiDung)
        ->whereNull('ThoiGianNopBai')
        ->first();

    if ($dangLam) {
        // Quay lại làm tiếp
        return redirect()->route('student.exams.do', [
            'id'     => $id,
            'baiLam' => $dangLam->MaBaiLam,
        ])->with('info', 'Bạn đang làm dở bài này. Tiếp tục từ chỗ đã làm!');
    }

    // Tạo bài làm mới
    DB::statement('CALL sp_BatDauLamBai(?, ?, @maBaiLam)', [$id, $maNguoiDung]);
    $result   = DB::select('SELECT @maBaiLam as maBaiLam');
    $maBaiLam = $result[0]->maBaiLam;

    return redirect()->route('student.exams.do', [
        'id'     => $id,
        'baiLam' => $maBaiLam,
    ]);
}
// Trang làm bài
    public function show($id)
    {
        $maBaiLam = request('baiLam');

        $deThi = DB::table('DeThi')->where('MaDeThi', $id)->first();
        if (!$deThi) abort(404);

        $baiLam = DB::table('BaiLamCuaHS')
            ->where('MaBaiLam', $maBaiLam)
            ->where('MaNguoiDung', Session::get('maNguoiDung'))
            ->first();

        if (!$baiLam) abort(403);

        // ── Tính giây đã làm bằng MySQL (tránh lệch timezone) ──
        $row = DB::selectOne(
            'SELECT TIMESTAMPDIFF(SECOND, ThoiGianBatDau, NOW()) as giayDaLam
             FROM BaiLamCuaHS WHERE MaBaiLam = ?',
            [$maBaiLam]
        );

        $tongGiay   = $deThi->ThoiGian * 60;
        $giayDaLam  = max(0, $row->giayDaLam ?? 0);
        $giayConLai = max(0, $tongGiay - $giayDaLam);

        // Debug tạm — xóa sau khi xác nhận đúng
        // dd([
        //     'ThoiGianBatDau' => $baiLam->ThoiGianBatDau,
        //     'tongGiay'       => $tongGiay,
        //     'giayDaLam'      => $giayDaLam,
        //     'giayConLai'     => $giayConLai,
        // ]);

        // Hết giờ → tự nộp
        if ($giayConLai <= 0) {
            DB::statement('CALL sp_NopBai(?)', [$maBaiLam]);
            return redirect()->route('student.results.show', $maBaiLam)
                ->with('info', 'Hết giờ — bài đã được nộp tự động.');
        }

        // ── Lấy câu hỏi ────────────────────────────────────────
        $phan1 = DB::table('CauHoiTrongDe as chtd')
            ->join('Question as q', 'q.MaCauHoi', '=', 'chtd.MaCauHoi')
            ->where('chtd.MaDeThi', $id)->where('chtd.Phan', 'I')
            ->orderBy('chtd.ThuTu')
            ->select('q.*', 'chtd.ThuTu', 'chtd.DiemCauHoi')
            ->get();

        foreach ($phan1 as $cau) {
            $cau->dapAn = DB::table('DapAnTN')
                ->where('MaCauHoi', $cau->MaCauHoi)
                ->orderBy('KyHieu')
                ->get();
        }

        $phan2 = DB::table('CauHoiTrongDe as chtd')
            ->join('Question as q', 'q.MaCauHoi', '=', 'chtd.MaCauHoi')
            ->where('chtd.MaDeThi', $id)->where('chtd.Phan', 'II')
            ->orderBy('chtd.ThuTu')
            ->select('q.*', 'chtd.ThuTu', 'chtd.DiemCauHoi')
            ->get();

        foreach ($phan2 as $cau) {
            $cau->cacY = DB::table('CauHoiDS_Y')
                ->where('MaCauHoi', $cau->MaCauHoi)
                ->orderBy('KyHieu')
                ->get();
        }

        $phan3 = DB::table('CauHoiTrongDe as chtd')
            ->join('Question as q', 'q.MaCauHoi', '=', 'chtd.MaCauHoi')
            ->where('chtd.MaDeThi', $id)->where('chtd.Phan', 'III')
            ->orderBy('chtd.ThuTu')
            ->select('q.*', 'chtd.ThuTu', 'chtd.DiemCauHoi')
            ->get();

        // ── Lấy đáp án đã lưu để restore ──────────────────────
        $daDuocChonTN = DB::table('ChiTietTraLoiTN')
            ->where('MaBaiLam', $maBaiLam)
            ->pluck('MaDATN', 'MaCauHoi');

        $daDuocChonDS = DB::table('ChiTietTraLoiDS')
            ->where('MaBaiLam', $maBaiLam)
            ->pluck('LuaChonCuaHocSinh', 'MaY');

        $daDuocChonSo = DB::table('ChiTietCauTraLoiSo')
            ->where('MaBaiLam', $maBaiLam)
            ->pluck('CauTraLoiSo', 'MaCauHoi');

        return view('student.exams.do', compact(
            'deThi', 'maBaiLam', 'phan1', 'phan2', 'phan3',
            'daDuocChonTN', 'daDuocChonDS', 'daDuocChonSo',
            'giayConLai'
        ));
    }

    // Lưu đáp án TN (AJAX)
    public function saveTN(Request $request)
    {
        DB::statement('CALL sp_LuuTraLoiTN(?, ?, ?)', [
            $request->maBaiLam,
            $request->maCauHoi,
            $request->maDATN,
        ]);
        return response()->json(['ok' => true]);
    }

    // Lưu đáp án Đúng/Sai (AJAX) - gửi từng ý
    public function saveDS(Request $request)
    {
        DB::statement('CALL sp_LuuTraLoiDS(?, ?, ?, ?)', [
            $request->maBaiLam,
            $request->maCauHoi,
            $request->maY,
            $request->luaChon, // 1 hoặc 0
        ]);
        return response()->json(['ok' => true]);
    }

    // Lưu đáp án số (AJAX)
    public function saveSo(Request $request)
    {
        DB::statement('CALL sp_LuuTraLoiSo(?, ?, ?)', [
            $request->maBaiLam,
            $request->maCauHoi,
            $request->soHocSinh,
        ]);
        return response()->json(['ok' => true]);
    }

    // Nộp bài
    public function submit(Request $request)
    {
        $maBaiLam = $request->maBaiLam;

        // Gọi SP chấm điểm
        DB::statement('CALL sp_NopBai(?)', [$maBaiLam]);

        return redirect()->route('student.results.show', $maBaiLam)
            ->with('success', 'Nộp bài thành công!');
    }
}