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
        Schema::create('orang_tuas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('siswa_id');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('nama_walimurid');
            $table->unsignedInteger('pendidikan_ayah_id');
            $table->unsignedInteger('pendidikan_ibu_id');
            $table->unsignedInteger('pendidikan_walimurid_id');
            $table->unsignedInteger('pekerjaan_ayah_id');
            $table->unsignedInteger('pekerjaan_ibu_id');
            $table->unsignedInteger('pekerjaan_walimurid_id');
            $table->string('hubungan_keluarga_walimurid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orang_tuas');
    }
};
