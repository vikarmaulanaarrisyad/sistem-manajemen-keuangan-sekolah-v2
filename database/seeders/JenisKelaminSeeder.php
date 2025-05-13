<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisKelamin; // Pastikan model sudah dibuat

class JenisKelaminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisKelaminList = [
            'Laki-laki',
            'Perempuan'
        ];

        foreach ($jenisKelaminList as $jenisKelamin) {
            JenisKelamin::firstOrCreate(['nama' => $jenisKelamin]);
        }
    }
}
