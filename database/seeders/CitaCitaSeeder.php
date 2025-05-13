<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CitaCita; // Pastikan model sudah dibuat

class CitaCitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $citaCitaList = [
            'Dokter',
            'Guru',
            'Polisi',
            'Tentara',
            'Pengusaha',
            'Insinyur',
            'Pilot',
            'Arsitek',
            'Seniman',
            'Atlet'
        ];

        foreach ($citaCitaList as $citaCita) {
            CitaCita::firstOrCreate(['nama' => $citaCita]);
        }
    }
}
