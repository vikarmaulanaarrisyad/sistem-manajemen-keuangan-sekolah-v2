<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agama; // Pastikan model Agama ada dan sudah dibuat

class AgamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agamaList = [
            'Islam',
            'Kristen Protestan',
            'Kristen Katolik',
            'Hindu',
            'Buddha',
            'Konghucu'
        ];

        foreach ($agamaList as $agama) {
            Agama::firstOrCreate(['nama' => $agama]);
        }
    }
}
