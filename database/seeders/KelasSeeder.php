<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = [
            ['tingkat' => 1, 'nama' => 'Kelas 1'],
            ['tingkat' => 2, 'nama' => 'Kelas 2'],
            ['tingkat' => 3, 'nama' => 'Kelas 3'],
            ['tingkat' => 4, 'nama' => 'Kelas 4'],
            ['tingkat' => 5, 'nama' => 'Kelas 5'],
            ['tingkat' => 6, 'nama' => 'Kelas 6'],
        ];

        foreach ($kelas as $data) {
            Kelas::firstOrCreate($data);
        }
    }
}
