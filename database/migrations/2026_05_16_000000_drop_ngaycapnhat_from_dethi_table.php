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
        if (Schema::hasTable('DeThi') && Schema::hasColumn('DeThi', 'NgayCapNhat')) {
            Schema::table('DeThi', function (Blueprint $table) {
                $table->dropColumn('NgayCapNhat');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('DeThi') && !Schema::hasColumn('DeThi', 'NgayCapNhat')) {
            Schema::table('DeThi', function (Blueprint $table) {
                $table->timestamp('NgayCapNhat')->nullable()->useCurrent();
            });
        }
    }
};
