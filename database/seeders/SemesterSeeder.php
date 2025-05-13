<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester; // Pastikan model sudah dibuat

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semesterList = [
            'Ganjil',
            'Genap',
        ];

        foreach ($semesterList as $semester) {
            Semester::firstOrCreate(['nama' => $semester]);
        }
    }
}
