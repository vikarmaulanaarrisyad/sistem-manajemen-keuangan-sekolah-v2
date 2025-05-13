<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tinggal; // Pastikan model sudah dibuat

class TinggalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tinggalList = [
            'Bersama Orang Tua',
            'Bersama Wali',
            'Kos',
            'Asrama',
            'Panti Asuhan',
            'Lainnya'
        ];

        foreach ($tinggalList as $tinggal) {
            Tinggal::firstOrCreate(['nama' => $tinggal]);
        }
    }
}
