<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ChiTietTraLoiDS extends Model {
    protected $table      = 'ChiTietTraLoiDS';
    protected $primaryKey = 'MaChiTietDS';
    public $timestamps    = false;

    protected $fillable = [
        'MaBaiLam','MaCauHoi','MaY',
        'LuaChonCuaHocSinh','DungSai'
    ];

    public function y() {
        return $this->belongsTo(CauHoiDS_Y::class, 'MaY');
    }
}
