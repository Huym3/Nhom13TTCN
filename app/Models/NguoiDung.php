<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model {
    protected $table      = 'NguoiDung';
    protected $primaryKey = 'MaNguoiDung';
    public $timestamps    = true;

    protected $fillable = [
        'TenDangNhap','MatKhau','Email',
        'HoTen','NgaySinh','Role','TrangThai','TeacherStatus'
    ];

    protected $casts = [
        'NgaySinh' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship
    public function baiLam() {
        return $this->hasMany(BaiLamCuaHS::class, 'MaNguoiDung');
    }

    public function questions() {
        return $this->hasMany(Question::class, 'MaNguoiTao');
    }

    // Methods
    public function isStudent() {
        return $this->Role === 'Student';
    }

    public function isTeacher() {
        return $this->Role === 'Teacher';
    }

    public function isAdmin() {
        return $this->Role === 'Admin';
    }

    public function isTeacherApproved() {
        return $this->Role === 'Teacher' && $this->TeacherStatus === 'Approved';
    }

    public function isTeacherPending() {
        return $this->Role === 'Teacher' && $this->TeacherStatus === 'Pending';
    }

    public function isActive() {
        return $this->TrangThai === 'Active';
    }

    public function isBlocked() {
        return $this->TrangThai === 'Banned';
    }
}
