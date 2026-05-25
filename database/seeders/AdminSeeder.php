<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra nếu admin đã tồn tại
        $existingAdmin = NguoiDung::where('TenDangNhap', 'admin')->first();

        if (!$existingAdmin) {
            NguoiDung::create([
                'TenDangNhap'   => 'admin',
                'MatKhau'       => Hash::make('admin1'),
                'Email'         => 'admin@thitest.local',
                'HoTen'         => 'Administrator',
                'NgaySinh'      => null,
                'Role'          => 'Admin',
                'TrangThai'     => 'Active',
                'TeacherStatus' => null,
            ]);

            echo "✅ Tài khoản Admin đã được tạo!\n";
            echo "Username: admin\n";
            echo "Password: admin1\n";
        } else {
            echo "⚠️ Tài khoản Admin đã tồn tại!\n";
        }
    }
}
