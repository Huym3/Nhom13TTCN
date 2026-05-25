<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CauHoiTrongDe extends Model {
    protected $table   = 'CauHoiTrongDe';
    public $timestamps = false;
    // Composite PK — Laravel không hỗ trợ trực tiếp, dùng như bảng pivot
    protected $fillable = ['MaCauHoi','MaDeThi','Phan','ThuTu','DiemCauHoi'];
}
