<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BaiLamCuaHS extends Model {
    protected $table      = 'BaiLamCuaHS';
    protected $primaryKey = 'MaBaiLam';
    public $timestamps    = false;

    protected $fillable = [
        'MaDeThi','MaNguoiDung','ThoiGianBatDau',
        'ThoiGianNopBai','TongThoiGianLamBai',
        'TongDiem','DiemPhan1','DiemPhan2','DiemPhan3',
        'SoCauDung','SoCauSai'
    ];

    public function deThi() {
        return $this->belongsTo(DeThi::class, 'MaDeThi');
    }
    public function hocSinh() {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung');
    }
    public function traLoiTN() {
        return $this->hasMany(ChiTietTraLoiTN::class, 'MaBaiLam');
    }
    public function traLoiDS() {
        return $this->hasMany(ChiTietTraLoiDS::class, 'MaBaiLam');
    }
    public function traLoiSo() {
        return $this->hasMany(ChiTietCauTraLoiSo::class, 'MaBaiLam');
    }
}
