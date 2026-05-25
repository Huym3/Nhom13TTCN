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
            'anhCauHoi'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'anhCauHoi.required' => 'Vui lòng upload ảnh câu hỏi.',
            'anhCauHoi.image'    => 'File phải là ảnh.',
            'anhCauHoi.max'      => 'Ảnh tối đa 5MB.',
        ]);

        // Upload ảnh câu hỏi
$anhPath = $request->hasFile('anhCauHoi')
    ? $request->file('anhCauHoi')->store('questions', 'public')
    : null;
        // Ảnh lưu tại: storage/app/public/questions/
        // Truy cập qua: /storage/questions/tenfile.jpg

        // Tạo câu hỏi — NoiDungCH lưu đường dẫn ảnh
 // Tạo câu hỏi
$maCauHoi = DB::table('Question')->insertGetId([
    'MaChuyenDe' => $request->maChuyenDe,
    'NoiDungCH'  => $request->noiDung ?: 'Xem nội dung trong hình ảnh',
    'HinhAnh'    => $anhPath,   // Lưu path vào đúng cột HinhAnh
    'LoaiCauHoi' => $request->loaiCauHoi,
    'DoKho'      => $request->doKho,
    'GiaiThich'  => $request->giaiThich,
    'MaNguoiTao' => Session::get('maNguoiDung'),
    'NgayTao'    => now(),
]);

// Phần I: TN
if ($request->loaiCauHoi === 'TN') {
    foreach (['A','B','C','D'] as $ky) {
        DB::table('DapAnTN')->insert([
            'MaCauHoi'     => $maCauHoi,
            'KyHieu'       => $ky,
            'NoiDungDapAn' => "Đáp án $ky", // Tránh để null
            'LaDapAnDung'  => $request->dapAnDung === $ky ? 1 : 0,
        ]);
    }
}
        // ── Phần II: DS — 4 ý a b c d ───────────────────────
        elseif ($request->loaiCauHoi === 'DS') {
    // Chỉ lưu ký hiệu và trạng thái đúng sai, nội dung để trống
    foreach (['a','b','c','d'] as $ky) {
        DB::table('CauHoiDS_Y')->insert([
            'MaCauHoi'  => $maCauHoi,
            'KyHieu'    => $ky,
            'NoiDungY'  => '', // Lưu chuỗi rỗng thay vì bắt nhập text
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
    'NoiDungCH'  => $request->noiDung ?: $question->NoiDungCH, // thêm dòng này
];

        // Nếu upload ảnh mới thì xóa ảnh cũ
// Nếu upload ảnh mới
        if ($request->hasFile('anhCauHoi')) {
            $request->validate(['anhCauHoi' => 'image|mimes:jpg,jpeg,png,webp|max:5120']);
            
            // Lấy ảnh cũ từ cột HinhAnh để xóa
            if ($question->HinhAnh) {
                Storage::disk('public')->delete($question->HinhAnh);
            }
            
            $data['HinhAnh'] = $request->file('anhCauHoi')->store('questions', 'public');
        }

        DB::table('Question')->where('MaCauHoi', $id)->update($data);

        if ($question->LoaiCauHoi === 'TN') {
    $dapAnDung = $request->dapAnDung;
    foreach (['A','B','C','D'] as $ky) {
        DB::table('DapAnTN')
            ->where('MaCauHoi', $id)
            ->where('KyHieu', $ky)
            ->update([
                'LaDapAnDung' => $dapAnDung === $ky ? 1 : 0,
            ]);
    }
}

        // Cập nhật ý DS
if ($question->LoaiCauHoi === 'DS') {
    foreach (['a', 'b', 'c', 'd'] as $ky) {
        DB::table('CauHoiDS_Y')
            ->where('MaCauHoi', $id)
            ->where('KyHieu', $ky)
            ->update([
                'NoiDungY'  => '', // Luôn cập nhật về rỗng hoặc giữ nguyên
                'DapAnDung' => $request->input('dapAnY_'.$ky)   
            ]);
    }
}

        // Cập nhật đáp án số
if ($question->LoaiCauHoi === 'TLS') {
    // Thêm validate để đảm bảo có dữ liệu trước khi update
    $request->validate([
        'dapAnSo' => 'required|numeric',
    ]);

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