<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kiểm tra nếu bảng NguoiDung không tồn tại thì tạo mới
        if (!Schema::hasTable('NguoiDung')) {
            Schema::create('NguoiDung', function (Blueprint $table) {
                $table->increments('MaNguoiDung');
                $table->string('TenDangNhap')->unique();
                $table->string('MatKhau');
                $table->string('Email')->unique();
                $table->string('HoTen');
                $table->date('NgaySinh')->nullable();
                
                // Thêm các cột mới
                $table->enum('Role', ['Student', 'Teacher', 'Admin'])->default('Student');
                $table->enum('TrangThai', ['Active', 'Banned'])->default('Active');
                
                // Cột này để lưu trạng thái của giáo viên (nếu là teacher)
                $table->enum('TeacherStatus', ['Pending', 'Approved', 'Rejected'])->nullable()->default('Pending');
                
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        } else {
            // Nếu bảng đã tồn tại, thêm các cột mới nếu chưa có
            Schema::table('NguoiDung', function (Blueprint $table) {
                if (!Schema::hasColumn('NguoiDung', 'Role')) {
                    $table->enum('Role', ['Student', 'Teacher', 'Admin'])->default('Student')->after('TrangThai');
                }
                if (!Schema::hasColumn('NguoiDung', 'TrangThai')) {
                    $table->enum('TrangThai', ['Active', 'Banned'])->default('Active')->after('NgaySinh');
                }
                if (!Schema::hasColumn('NguoiDung', 'TeacherStatus')) {
                    $table->enum('TeacherStatus', ['Pending', 'Approved', 'Rejected'])->nullable()->default('Pending')->after('Role');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('NguoiDung')) {
            Schema::table('NguoiDung', function (Blueprint $table) {
                if (Schema::hasColumn('NguoiDung', 'Role')) {
                    $table->dropColumn('Role');
                }
                if (Schema::hasColumn('NguoiDung', 'TeacherStatus')) {
                    $table->dropColumn('TeacherStatus');
                }
            });
        }
    }
};
