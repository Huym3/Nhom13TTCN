<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    // Danh sách câu hỏi
    public function index()
    {
        $questions = DB::table('Question as q')
            ->join('ChuyenDe as cd', 'cd.MaChuyenDe', '=', 'q.MaChuyenDe')
            ->where('q.MaNguoiTao', Session::get('maNguoiDung'))
            ->orderBy('q.NgayTao', 'desc')
            ->select('q.*', 'cd.TenChuyenDe')
            ->get();

        return view('teacher.questions.index', compact('questions'));
    }

    // Form tạo câu hỏi
    public function create()
    {
        $chuyenDe = DB::table('ChuyenDe')->get();
        return view('teacher.questions.create', compact('chuyenDe'));
    }

    // Lưu câu hỏi mới
    public function store(Request $request)
    {
        $request->validate([
            'maChuyenDe' => 'required',
            'loaiCauHoi' => 'required|in:TN,DS,TLS',
            'doKho'      => 'required',
            'anhCauHoi'  => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'anhCauHoi.required' => 'Vui lòng upload ảnh câu hỏi.',
            'anhCauHoi.image'    => 'File phải là ảnh.',
            'anhCauHoi.max'      => 'Ảnh tối đa 5MB.',
        ]);

        // Upload ảnh câu hỏi
        $anhPath = $request->file('anhCauHoi')
            ->store('questions', 'public');
        // Ảnh lưu tại: storage/app/public/questions/
        // Truy cập qua: /storage/questions/tenfile.jpg

        // Tạo câu hỏi — NoiDungCH lưu đường dẫn ảnh
        $maCauHoi = DB::table('Question')->insertGetId([
            'MaChuyenDe' => $request->maChuyenDe,
            'NoiDungCH'  => $anhPath,   // lưu path ảnh
            'LoaiCauHoi' => $request->loaiCauHoi,
            'DoKho'      => $request->doKho,
            'GiaiThich'  => $request->giaiThich,
            'MaNguoiTao' => Session::get('maNguoiDung'),
            'NgayTao'    => now(),
        ]);


// ── Phần I: TN — Chỉ lưu ký hiệu và đáp án đúng ──────────────
if ($request->loaiCauHoi === 'TN') {
    $request->validate([
        'dapAnDung' => 'required|in:A,B,C,D',
    ]);

    foreach (['A','B','C','D'] as $ky) {
        DB::table('DapAnTN')->insert([
            'MaCauHoi'     => $maCauHoi,
            'KyHieu'       => $ky,
            'NoiDungDapAn' => null, // Hoặc để trống nếu cột này cho phép null
            'LaDapAnDung'  => $request->dapAnDung === $ky ? 1 : 0,
        ]);
    }
}
        // ── Phần II: DS — 4 ý a b c d ───────────────────────
        elseif ($request->loaiCauHoi === 'DS') {
            $request->validate([
                'noiDungY_a' => 'required|string',
                'noiDungY_b' => 'required|string',
                'noiDungY_c' => 'required|string',
                'noiDungY_d' => 'required|string',
            ]);

            foreach (['a','b','c','d'] as $ky) {
                DB::table('CauHoiDS_Y')->insert([
                    'MaCauHoi'  => $maCauHoi,
                    'KyHieu'    => $ky,
                    'NoiDungY'  => $request->input('noiDungY_'.$ky),
                    'DapAnDung' => $request->input('dapAnY_'.$ky) ? 1 : 0,
                ]);
            }
        }

        // ── Phần III: TLS — đáp án số ────────────────────────
        elseif ($request->loaiCauHoi === 'TLS') {
            $request->validate([
                'dapAnSo' => 'required|numeric',
            ]);

            DB::table('DapAnTLS')->insert([
                'MaCauHoi'      => $maCauHoi,
                'DapAnSo'       => $request->dapAnSo,
                'SaiSoChapNhan' => $request->saiSo ?? 0.005,
                'GhiChu'        => $request->ghiChu,
            ]);
        }

        return redirect()->route('teacher.questions.index')
            ->with('success', 'Tạo câu hỏi thành công!');
    }

    // Form sửa
    public function edit($id)
    {
        $question = DB::table('Question')->where('MaCauHoi', $id)->first();
        if (!$question) abort(404);

        // Chỉ giáo viên tạo câu hỏi mới được sửa
        if ($question->MaNguoiTao != Session::get('maNguoiDung')) abort(403);

        $chuyenDe = DB::table('ChuyenDe')->get();
        $dapAnTN  = DB::table('DapAnTN')->where('MaCauHoi', $id)->get();
        $cacY     = DB::table('CauHoiDS_Y')->where('MaCauHoi', $id)->get();
        $dapAnSo  = DB::table('DapAnTLS')->where('MaCauHoi', $id)->first();

        return view('teacher.questions.edit',
            compact('question','chuyenDe','dapAnTN','cacY','dapAnSo'));
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $question = DB::table('Question')->where('MaCauHoi', $id)->first();
        if ($question->MaNguoiTao != Session::get('maNguoiDung')) abort(403);

        $data = [
            'MaChuyenDe' => $request->maChuyenDe,
            'DoKho'      => $request->doKho,
            'GiaiThich'  => $request->giaiThich,
        ];

        // Nếu upload ảnh mới thì xóa ảnh cũ
        if ($request->hasFile('anhCauHoi')) {
            $request->validate([
                'anhCauHoi' => 'image|mimes:jpg,jpeg,png,webp|max:5120'
            ]);
            Storage::disk('public')->delete($question->NoiDungCH);
            $data['NoiDungCH'] = $request->file('anhCauHoi')
                ->store('questions', 'public');
        }

        DB::table('Question')->where('MaCauHoi', $id)->update($data);


        // Cập nhật ý DS
        if ($question->LoaiCauHoi === 'DS') {
            foreach (['a','b','c','d'] as $ky) {
                DB::table('CauHoiDS_Y')
                    ->where('MaCauHoi', $id)
                    ->where('KyHieu', $ky)
                    ->update([
                        'NoiDungY'  => $request->input('noiDungY_'.$ky),
                        'DapAnDung' => $request->input('dapAnY_'.$ky) ? 1 : 0,
                    ]);
            }
        }

        // Cập nhật đáp án số
        if ($question->LoaiCauHoi === 'TLS') {
            DB::table('DapAnTLS')
                ->where('MaCauHoi', $id)
                ->update([
                    'DapAnSo'       => $request->dapAnSo,
                    'SaiSoChapNhan' => $request->saiSo ?? 0.005,
                ]);
        }

        return redirect()->route('teacher.questions.index')
            ->with('success', 'Cập nhật thành công!');
    }

    // Xóa câu hỏi
   public function destroy($id)
{
    // Lấy tất cả MaDATN của câu hỏi này
    $maDATNs = DB::table('DapAnTN')
        ->where('MaCauHoi', $id)
        ->pluck('MaDATN');

    // Xóa chi tiết trả lời TN của học sinh trước
    if ($maDATNs->isNotEmpty()) {
        DB::table('ChiTietTraLoiTN')
            ->whereIn('MaDATN', $maDATNs)
            ->delete();
    }

    // Xóa chi tiết trả lời DS
    $maYs = DB::table('CauHoiDS_Y')
        ->where('MaCauHoi', $id)
        ->pluck('MaY');

    if ($maYs->isNotEmpty()) {
        DB::table('ChiTietTraLoiDS')
            ->whereIn('MaY', $maYs)
            ->delete();
    }

    // Xóa chi tiết trả lời số
    DB::table('ChiTietCauTraLoiSo')->where('MaCauHoi', $id)->delete();

    // Xóa chi tiết trả lời TN theo MaCauHoi (phòng trường hợp còn sót)
    DB::table('ChiTietTraLoiTN')->where('MaCauHoi', $id)->delete();

    // Xóa bình luận liên quan
    DB::table('BinhLuan')->where('MaCauHoi', $id)->delete();

    // Xóa khỏi đề thi
    DB::table('CauHoiTrongDe')->where('MaCauHoi', $id)->delete();

    // Xóa đáp án
    DB::table('DapAnTN')->where('MaCauHoi', $id)->delete();
    DB::table('CauHoiDS_Y')->where('MaCauHoi', $id)->delete();
    DB::table('DapAnTLS')->where('MaCauHoi', $id)->delete();

    // Xóa ảnh nếu có
    $hinhAnh = DB::table('Question')->where('MaCauHoi', $id)->value('HinhAnh');
    if ($hinhAnh) Storage::disk('public')->delete($hinhAnh);

    // Xóa câu hỏi
    DB::table('Question')->where('MaCauHoi', $id)->delete();

    return redirect()->route('teacher.questions.index')
        ->with('success', 'Xóa câu hỏi thành công!');
}
}