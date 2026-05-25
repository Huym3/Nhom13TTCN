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

    public function isApprovedTeacher() {
        return $this->Role === 'Teacher' && $this->TeacherStatus === 'Approved';
    }

    public function isAdmin() {
        return $this->Role === 'Admin';
    }

    public function isTeacherApproved() {
        return $this->isApprovedTeacher();
    }

    public function isTeacherPending() {
        return $this->Role === 'Teacher'
            && in_array($this->TeacherStatus, ['Pending', null], true);
    }

    public function isActive() {
        return $this->TrangThai === 'Active'
            && ($this->Role !== 'Teacher' || $this->TeacherStatus === 'Approved');
    }

    public function isBlocked() {
        return $this->TrangThai === 'Banned';
    }
}
