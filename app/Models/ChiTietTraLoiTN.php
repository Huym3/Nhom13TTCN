<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ChiTietTraLoiTN extends Model {
    protected $table      = 'ChiTietTraLoiTN';
    protected $primaryKey = 'MaChiTietTN';
    public $timestamps    = false;

    protected $fillable = ['MaBaiLam','MaCauHoi','MaDATN','DungSai','DiemDatDuoc'];

    public function dapAn() {
        return $this->belongsTo(DapAnTN::class, 'MaDATN');
    }
    public function question() {
        return $this->belongsTo(Question::class, 'MaCauHoi');
    }
}
