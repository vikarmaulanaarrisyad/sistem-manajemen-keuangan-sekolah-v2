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
        Schema::create('perpindahan_kelas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('siswa_id');
            $table->unsignedInteger('kelas_lama_id');
            $table->unsignedInteger('kelas_baru_id');
            $table->date('tanggal_pindah');
            $table->text('alasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perpindahan_kelas');
    }
};
