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
        Schema::create('pemasukan_bos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sumber');
            $table->integer('jumlah');
            $table->date('tanggal_terima');
            $table->unsignedBigInteger('tahun_pelajaran_id');
            $table->string('keterangan')->nullable();
            $table->integer('status')->default(0); // 1 : aktif, 0 : tidak aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan_bos');
    }
};
