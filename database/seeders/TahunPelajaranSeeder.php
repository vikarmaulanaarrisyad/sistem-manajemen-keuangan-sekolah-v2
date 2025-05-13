<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TahunPelajaran; // Pastikan model sudah dibuat
use App\Models\Semester;

class TahunPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunPelajaranList = [
            ['semester_id' => 2, 'nama' => '2024/2025', 'status' => '1'],
        ];

        foreach ($tahunPelajaranList as $tahunPelajaran) {
            TahunPelajaran::firstOrCreate([
                'semester_id' => $tahunPelajaran['semester_id'],
                'nama' => $tahunPelajaran['nama']
            ], [
                'status' => $tahunPelajaran['status']
            ]);
        }
    }
}
