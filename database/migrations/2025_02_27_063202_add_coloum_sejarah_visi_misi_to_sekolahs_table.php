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
        Schema::table('sekolahs', function (Blueprint $table) {
            $table->longText('sejarah')->nullable();
            $table->longText('visi')->nullable();
            $table->longText('misi')->nullable();
            $table->text('opening')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolahs', function (Blueprint $table) {
            $table->dropColumn([
                'sejarah',
                'visi',
                'misi',
                'opening'
            ]);
        });
    }
};
