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

        // Không cho xóa nếu đã có bài làm
        $soBaiLam = DB::table('BaiLamCuaHS')->where('MaDeThi', $id)->count();
        if ($soBaiLam > 0) {
            return redirect()->route('teacher.exams.index')
                ->with('error', 'Không thể xóa đề đã có học sinh làm bài.');
        }

        DB::table('CauHoiTrongDe')->where('MaDeThi', $id)->delete();
        DB::table('DeThi')->where('MaDeThi', $id)->delete();

        return redirect()->route('teacher.exams.index')
            ->with('success', 'Xóa đề thi thành công!');
    }

    // ── Thêm câu hỏi vào đề ─────────────────────────────────
    public function addQuestion(Request $request, $id)
    {
        $request->validate([
            'maCauHoi' => 'required|integer',
            'phan'     => 'required|in:I,II,III',
        ]);

        $exam = DB::table('DeThi')->where('MaDeThi', $id)->first();
        if (!$exam) abort(404);

        // Kiểm tra loại câu hỏi có khớp với phần không
        $question = DB::table('Question')->where('MaCauHoi', $request->maCauHoi)->first();
        if (!$question) abort(404);

        $loaiHopLe = [
            'I'   => 'TN',
            'II'  => 'DS',
            'III' => 'TLS',
        ];

        if ($loaiHopLe[$request->phan] !== $question->LoaiCauHoi) {
            return back()->with('error', 'Loại câu hỏi không khớp với phần đã chọn (Phần I: TN, Phần II: DS, Phần III: TLS).');
        }

        // Kiểm tra đã có chưa
        $daCoTrongDe = DB::table('CauHoiTrongDe')
            ->where('MaCauHoi', $request->maCauHoi)
            ->where('MaDeThi', $id)
            ->exists();

        if ($daCoTrongDe) {
            return back()->with('error', 'Câu hỏi này đã có trong đề.');
        }

        // Điểm theo phần
        $diemTheoPhan = ['I' => 0.25, 'II' => 1.00, 'III' => 0.50];

        // Số thứ tự tiếp theo trong phần
        $thuTu = DB::table('CauHoiTrongDe')
            ->where('MaDeThi', $id)
            ->where('Phan', $request->phan)
            ->max('ThuTu') + 1;

        DB::table('CauHoiTrongDe')->insert([
            'MaCauHoi'   => $request->maCauHoi,
            'MaDeThi'    => $id,
            'Phan'       => $request->phan,
            'ThuTu'      => $thuTu,
            'DiemCauHoi' => $diemTheoPhan[$request->phan],
        ]);

        // Cập nhật SoCauHoi
        $tong = DB::table('CauHoiTrongDe')->where('MaDeThi', $id)->count();
        DB::table('DeThi')->where('MaDeThi', $id)->update([
            'SoCauHoi'   => $tong,
            'NgayCapNhat'=> now(),
        ]);

        return back()->with('success', 'Đã thêm câu hỏi vào đề.');
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
        $phanBoDiem = DB::table('BaiLamCuaHS')
            ->where('MaDeThi', $id)
            ->whereNotNull('ThoiGianNopBai')
            ->selectRaw("
                CASE
                    WHEN TongDiem < 2  THEN '0 - 2'
                    WHEN TongDiem < 4  THEN '2 - 4'
                    WHEN TongDiem < 5  THEN '4 - 5'
                    WHEN TongDiem < 6.5 THEN '5 - 6.5'
                    WHEN TongDiem < 8  THEN '6.5 - 8'
                    ELSE '8 - 10'
                END as KhoangDiem,
                COUNT(*) as SoLuong
            ")
            ->groupByRaw("KhoangDiem")
            ->orderByRaw("MIN(TongDiem)")
            ->get();

        // Top 5 học sinh điểm cao
        $topHocSinh = DB::table('BaiLamCuaHS as bl')
            ->join('NguoiDung as nd', 'nd.MaNguoiDung', '=', 'bl.MaNguoiDung')
            ->where('bl.MaDeThi', $id)
            ->whereNotNull('bl.ThoiGianNopBai')
            ->orderByDesc('bl.TongDiem')
            ->orderBy('bl.TongThoiGianLamBai')
            ->limit(5)
            ->select('nd.HoTen', 'bl.TongDiem', 'bl.SoCauDung', 'bl.TongThoiGianLamBai', 'bl.ThoiGianNopBai')
            ->get();

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
            ->groupBy('ctd.MaCauHoi', 'q.NoiDungCH', 'ctd.ThuTu')
            ->selectRaw('ctd.ThuTu, LEFT(q.NoiDungCH, 60) as NoiDung,
                COUNT(ct.MaChiTietTN) as TongTraLoi,
                SUM(ct.DungSai) as SoDung')
            ->get();

        return view('teacher.exams.stats', compact(
            'exam', 'tongQuan', 'phanBoDiem', 'topHocSinh', 'tiLeUngCauTN'
        ));
    }
}