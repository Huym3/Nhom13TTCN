<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Question extends Model {
    protected $table      = 'Question';
    protected $primaryKey = 'MaCauHoi';
    public $timestamps    = false;

    protected $fillable = [
        'MaChuyenDe', 
        'NoiDungCH', 
        'HinhAnh', // Bắt buộc phải thêm cột này để lưu tên file ảnh
        'DoKho',
        'LoaiCauHoi', 
        'GiaiThich', 
        'MaNguoiTao'
    ];

    public function chuyenDe() {
        return $this->belongsTo(ChuyenDe::class, 'MaChuyenDe');
    }
    public function nguoiTao() {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiTao');
    }
    // Đáp án TN (Phần I)
    public function dapAnTN() {
        return $this->hasMany(DapAnTN::class, 'MaCauHoi');
    }
    // Các ý Đúng/Sai (Phần II)
    public function cauHoiDSY() {
        return $this->hasMany(CauHoiDS_Y::class, 'MaCauHoi');
    }
    // Đáp án số (Phần III)
    public function dapAnTLS() {
        return $this->hasOne(DapAnTLS::class, 'MaCauHoi');
    }
    // Các đề chứa câu hỏi này
    public function deThi() {
        return $this->belongsToMany(DeThi::class, 'CauHoiTrongDe', 'MaCauHoi', 'MaDeThi')
                    ->withPivot('Phan','ThuTu','DiemCauHoi');
    }
}