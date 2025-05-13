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
        Schema::create('mutasi_siswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('siswa_id');
            $table->enum('jenis', ['Pindah Masuk', 'Pindah Keluar', 'Drop Out', 'Siswa Baru', 'Lulus']);
            $table->date('tanggal_mutasi');
            $table->integer('tingkat_sekolah_selanjutnya')->nullable();
            $table->string('sekolah_tujuan')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->text('alasan')->nullable();
            $table->string('noijazah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_siswas');
    }
};
