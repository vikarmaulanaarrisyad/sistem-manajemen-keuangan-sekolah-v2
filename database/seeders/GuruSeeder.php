<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gurus = [
            [
                'user_id' => 2,
                'nama_lengkap' => 'Ahmad Fauzi',
                'tempat_lahir' => 'Jakarta',
                'tgl_lahir' => '1985-07-15',
                'tmt_guru' => '2010-08-01',
                'tmt_pegawai' => '2011-01-10',
                'jenis_kelamin_id' => 1,
            ],
        ];

        foreach ($gurus as $data) {
            Guru::firstOrCreate(['user_id' => $data['user_id']], $data);
        }
    }
}
