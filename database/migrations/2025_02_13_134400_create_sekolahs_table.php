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
        Schema::create('sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->bigInteger('npsn');
            $table->bigInteger('nsm');
            $table->text('alamat');
            $table->bigInteger('notelp')->default(0);
            $table->text('email');
            $table->text('website');
            $table->unsignedInteger('kepala_sekolah_id');
            $table->unsignedInteger('bendahara_id');
            $table->string('logo')->default('default.jpg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolahs');
    }
};
