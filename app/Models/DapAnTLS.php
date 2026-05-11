<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DapAnTLS extends Model {
    protected $table      = 'DapAnTLS';
    protected $primaryKey = 'MaDATLS';
    public $timestamps    = false;

    protected $fillable = ['MaCauHoi','DapAnSo','SaiSoChapNhan','GhiChu'];
}