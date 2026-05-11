<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ChiTietCauTraLoiSo extends Model {
    protected $table      = 'ChiTietCauTraLoiSo';
    protected $primaryKey = 'MaCauTLSo';
    public $timestamps    = false;

    protected $fillable = ['MaBaiLam','MaCauHoi','CauTraLoiSo','DungSai','DiemDatDuoc'];
}