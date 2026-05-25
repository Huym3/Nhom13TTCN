<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BinhLuan extends Model {
    protected $table      = 'BinhLuan';
    protected $primaryKey = 'MaBinhLuan';
    public $timestamps    = false;

    protected $fillable = [
        'MaNguoiDung','MaCauHoi','MaBaiLam',
        'MaBinhLuanCha','NoiDungBinhLuan'
    ];

    public function nguoiDung() {
        return $this->belongsTo(NguoiDung::class, 'MaNguoiDung');
    }
    public function replies() {
        return $this->hasMany(BinhLuan::class, 'MaBinhLuanCha');
    }
    public function parent() {
        return $this->belongsTo(BinhLuan::class, 'MaBinhLuanCha');
    }
}