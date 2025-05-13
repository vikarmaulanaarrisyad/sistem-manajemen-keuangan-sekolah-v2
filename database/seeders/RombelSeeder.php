<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rombel;

class RombelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rombels = [
            [
                'kelas_id' => 1,
                'nama' => 'A',
                'kurikulum_id' => 2,
                'tahun_pelajaran_id' => 1,
                'wali_kelas_id' => null,
            ],
            [
                'kelas_id' => 1,
                'nama' => 'B',
                'kurikulum_id' => 2,
                'tahun_pelajaran_id' => 1,
                'wali_kelas_id' => 1,
            ],
            [
                'kelas_id' => 2,
                'nama' => 'A',
                'kurikulum_id' => 2,
                'tahun_pelajaran_id' => 1,
                'wali_kelas_id' => null,
            ],
            [
                'kelas_id' => 3,
                'nama' => 'A',
                'kurikulum_id' => 1,
                'tahun_pelajaran_id' => 1,
                'wali_kelas_id' => null,
            ],
            [
                'kelas_id' => 4,
                'nama' => 'A',
                'kurikulum_id' => 2,
                'tahun_pelajaran_id' => 1,
                'wali_kelas_id' => null,
            ],
            [
                'kelas_id' => 5,
                'nama' => 'A',
                'kurikulum_id' => 2,
                'tahun_pelajaran_id' => 1,
                'wali_kelas_id' => null,
            ],
            [
                'kelas_id' => 6,
                'nama' => 'A',
                'kurikulum_id' => 1,
                'tahun_pelajaran_id' => 1,
                'wali_kelas_id' => null,
            ],
        ];

        foreach ($rombels as $data) {
            Rombel::firstOrCreate([
                'kelas_id' => $data['kelas_id'],
                'nama' => $data['nama'],
                'kurikulum_id' => $data['kurikulum_id'],
                'tahun_pelajaran_id' => $data['tahun_pelajaran_id'],
                'wali_kelas_id' => $data['wali_kelas_id'],
            ]);
        }
    }
}
