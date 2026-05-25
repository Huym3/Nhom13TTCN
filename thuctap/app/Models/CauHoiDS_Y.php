<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CauHoiDS_Y extends Model {
    protected $table      = 'CauHoiDS_Y';
    protected $primaryKey = 'MaY';
    public $timestamps    = false;

    protected $fillable = ['MaCauHoi','KyHieu','NoiDungY','DapAnDung'];

    public function question() {
        return $this->belongsTo(Question::class, 'MaCauHoi');
    }
}
