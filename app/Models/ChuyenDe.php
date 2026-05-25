<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ChuyenDe extends Model {
    protected $table      = 'ChuyenDe';
    protected $primaryKey = 'MaChuyenDe';
    public $timestamps    = false;

    protected $fillable = ['TenChuyenDe','MoTaCD'];

    public function questions() {
        return $this->hasMany(Question::class, 'MaChuyenDe');
    }
}