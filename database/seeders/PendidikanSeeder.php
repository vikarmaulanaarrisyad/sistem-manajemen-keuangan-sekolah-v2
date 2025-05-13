<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pendidikan; // Pastikan model sudah dibuat

class PendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pendidikanList = [
            'SD/MI',
            'SMP/MTS',
            'SMA/SMK',
            'D3',
            'S1/D4',
            'S2',
            'S3'
        ];

        foreach ($pendidikanList as $pendidikan) {
            Pendidikan::firstOrCreate(['nama' => $pendidikan]);
        }
    }
}
