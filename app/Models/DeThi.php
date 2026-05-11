<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeThi extends Model {
    protected $table      = 'DeThi';
    protected $primaryKey = 'MaDeThi';
    public $timestamps    = false;

    protected $fillable = [
        'TenDeThi','MaDe','ThoiGian','SoCauHoi',
        'CauTrucDe','MaNguoiTaoDe','TrangThaiDe'
    ];

    public function nguoiTao() {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiTaoDe');
    }
    public function questions() {
        return $this->belongsToMany(Question::class, 'CauHoiTrongDe', 'MaDeThi', 'MaCauHoi')
                    ->withPivot('Phan','ThuTu','DiemCauHoi')
                    ->orderBy('Phan')->orderBy('ThuTu');
    }
    public function baiLam() {
        return $this->hasMany(BaiLamCuaHS::class, 'MaDeThi');
    }
}
