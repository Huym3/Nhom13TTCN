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
        $deThi = DB::table('DeThi')
            ->where('TrangThaiDe', 'Published')
            ->orderBy('NgayTao', 'desc')
            ->get();

        return view('student.exams.index', compact('deThi'));
    }

    // Bắt đầu làm bài → gọi SP tạo bài làm
    public function start($id)
    {
        $maNguoiDung = Session::get('maNguoiDung');

        // Kiểm tra đã làm bài này chưa
        $daLam = DB::table('BaiLamCuaHS')
            ->where('MaDeThi', $id)
            ->where('MaNguoiDung', $maNguoiDung)
            ->whereNotNull('ThoiGianNopBai')
            ->first();

        if ($daLam) {
            return redirect()->route('student.results.show', $daLam->MaBaiLam)
                ->with('error', 'Bạn đã làm bài này rồi!');
        }

        // Gọi stored procedure tạo bài làm
        DB::statement('CALL sp_BatDauLamBai(?, ?, @maBaiLam)', [$id, $maNguoiDung]);
        $result   = DB::select('SELECT @maBaiLam as maBaiLam');
        $maBaiLam = $result[0]->maBaiLam;

        return redirect()->route('student.exams.do', [
            'id'       => $id,
            'baiLam'   => $maBaiLam
        ]);
    }

    // Trang làm bài
    public function show($id)
    {
        $maBaiLam = request('baiLam');

        // Lấy thông tin đề thi
        $deThi = DB::table('DeThi')->where('MaDeThi', $id)->first();
        if (!$deThi) abort(404);

        // Lấy câu hỏi theo từng phần
        $phan1 = DB::table('CauHoiTrongDe as chtd')
            ->join('Question as q', 'q.MaCauHoi', '=', 'chtd.MaCauHoi')
            ->where('chtd.MaDeThi', $id)
            ->where('chtd.Phan', 'I')
            ->orderBy('chtd.ThuTu')
            ->select('q.*', 'chtd.ThuTu', 'chtd.DiemCauHoi')
            ->get();

        // Lấy đáp án TN cho từng câu Phần I
        foreach ($phan1 as $cau) {
            $cau->dapAn = DB::table('DapAnTN')
                ->where('MaCauHoi', $cau->MaCauHoi)
                ->get();
        }

        $phan2 = DB::table('CauHoiTrongDe as chtd')
            ->join('Question as q', 'q.MaCauHoi', '=', 'chtd.MaCauHoi')
            ->where('chtd.MaDeThi', $id)
            ->where('chtd.Phan', 'II')
            ->orderBy('chtd.ThuTu')
            ->select('q.*', 'chtd.ThuTu', 'chtd.DiemCauHoi')
            ->get();

        // Lấy các ý con cho từng câu Phần II
        foreach ($phan2 as $cau) {
            $cau->cacY = DB::table('CauHoiDS_Y')
                ->where('MaCauHoi', $cau->MaCauHoi)
                ->orderBy('KyHieu')
                ->get();
        }

        $phan3 = DB::table('CauHoiTrongDe as chtd')
            ->join('Question as q', 'q.MaCauHoi', '=', 'chtd.MaCauHoi')
            ->where('chtd.MaDeThi', $id)
            ->where('chtd.Phan', 'III')
            ->orderBy('chtd.ThuTu')
            ->select('q.*', 'chtd.ThuTu', 'chtd.DiemCauHoi')
            ->get();

        return view('student.exams.do', compact(
            'deThi', 'maBaiLam', 'phan1', 'phan2', 'phan3'
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