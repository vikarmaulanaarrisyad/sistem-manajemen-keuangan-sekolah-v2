<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Aplikasi; // Pastikan model sudah ada

class AplikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Aplikasi::firstOrCreate(
            ['singkatan' => 'SIMAD'], // Cek berdasarkan singkatan
            [
                'singkatan' => 'SIMAD',
                'nama' => 'Sistem Informasi Madrasah',
                'copyright' => '© 2025 by Vikar Maulana',
            ]
        );
    }
}
