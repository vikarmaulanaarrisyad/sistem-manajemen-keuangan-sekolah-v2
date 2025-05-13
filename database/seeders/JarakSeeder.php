<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jarak; // Pastikan model sudah dibuat

class JarakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jarakList = [
            'Kurang dari 1 km',
            '1 - 3 km',
            '3 - 5 km',
            '5 - 10 km',
            'Lebih dari 10 km'
        ];

        foreach ($jarakList as $jarak) {
            Jarak::firstOrCreate(['nama' => $jarak]);
        }
    }
}
