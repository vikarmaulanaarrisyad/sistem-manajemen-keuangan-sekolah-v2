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
        Schema::create('pengeluaran_bos', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pengeluaran');
            $table->string('uraian');
            $table->integer('jumlah');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tahun_pelajaran_id')->constrained('tahun_pelajarans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_bos');
    }
};
