<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class NguoiDung extends Model {
    protected $table      = 'NguoiDung';
    protected $primaryKey = 'MaNguoiDung';
    public $timestamps    = false;

    protected $fillable = [
        'TenDangNhap','MatKhau','Email',
        'HoTen','NgaySinh','Role','TrangThai'
    ];

    public function baiLam() {
        return $this->hasMany(BaiLamCuaHS::class, 'MaNguoiDung');
    }
    public function questions() {
        return $this->hasMany(Question::class, 'MaNguoiTao');
    }
}