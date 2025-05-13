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
        Schema::create('keadaan_jasmanis', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun')->default();
            $table->unsignedInteger('siswa_id');
            $table->integer('tinggi_badan')->default(0);
            $table->integer('berat_badan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keadaan_jasmanis');
    }
};
