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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('rombel_sebelumnya_id')->nullable();
            $table->integer('level')->default(1);
            $table->bigInteger('nisn')->default(0);
            $table->bigInteger('nik')->default(0);
            $table->bigInteger('nis')->default(0);
            $table->bigInteger('kk')->default(0);
            $table->string('nama_lengkap');
            $table->string('nama_panggilan');
            $table->unsignedInteger('jenis_kelamin_id');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->unsignedInteger('agama_id');
            $table->unsignedInteger('kewarganegaraan_id');
            $table->integer('jumlah_saudara')->default(0);
            $table->integer('anakke')->default(0);
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['Aktif', 'Lulus', 'Pindah'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
