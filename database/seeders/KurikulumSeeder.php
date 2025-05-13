<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kurikulum; // Pastikan model sudah dibuat
use App\Models\TahunPelajaran;

class KurikulumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kurikulumList = [
            ['nama' => 'Kurikulum 2013', 'tahun_pelajaran_id' => '1'],
            ['nama' => 'Kurikulum Merdeka', 'tahun_pelajaran_id' => '1'],
        ];

        foreach ($kurikulumList as $kurikulum) {
            Kurikulum::firstOrCreate([
                'nama' => $kurikulum['nama'],
                'tahun_pelajaran_id' => $kurikulum['tahun_pelajaran_id']
            ]);
        }
    }
}
