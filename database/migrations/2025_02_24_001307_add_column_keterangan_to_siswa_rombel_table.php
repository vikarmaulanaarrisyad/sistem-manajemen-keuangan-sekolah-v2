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
        Schema::table('siswa_rombel', function (Blueprint $table) {
            $table->string('keterangan')->default('Siswa Baru');
            $table->string('status')->default('Aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa_rombel', function (Blueprint $table) {
            $table->dropColumn(['keterangan', 'status']);
        });
    }
};
