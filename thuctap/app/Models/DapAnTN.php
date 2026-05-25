<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DapAnTN extends Model {
    protected $table      = 'DapAnTN';
    protected $primaryKey = 'MaDATN';
    public $timestamps    = false;

    protected $fillable = ['MaCauHoi','KyHieu','NoiDungDapAn','LaDapAnDung'];

    public function question() {
        return $this->belongsTo(Question::class, 'MaCauHoi');
    }
}
