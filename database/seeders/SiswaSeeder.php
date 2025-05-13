<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = [
            [
                'nisn' => 1234567890,
                'nik' => 3201234567890001,
                'nis' => 21001,
                'kk' => 3201234567890001,
                'nama_lengkap' => 'Ahmad Rizky',
                'nama_panggilan' => 'Rizky',
                'jenis_kelamin_id' => 1, // 1 = Laki-laki, 2 = Perempuan
                'tempat_lahir' => 'Jakarta',
                'tgl_lahir' => '2010-05-10',
                'agama_id' => 1, // 1 = Islam
                'kewarganegaraan_id' => 1, // 1 = WNI
                'jumlah_saudara' => 2,
                'anakke' => 1,
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
                'foto' => null,
            ],
            [
                'nisn' => 1234567891,
                'nik' => 3201234567890002,
                'nis' => 21002,
                'kk' => 3201234567890002,
                'nama_lengkap' => 'Siti Aisyah',
                'nama_panggilan' => 'Aisyah',
                'jenis_kelamin_id' => 2, // 1 = Laki-laki, 2 = Perempuan
                'tempat_lahir' => 'Bandung',
                'tgl_lahir' => '2011-03-15',
                'agama_id' => 1, // 1 = Islam
                'kewarganegaraan_id' => 1, // 1 = WNI
                'jumlah_saudara' => 3,
                'anakke' => 2,
                'alamat' => 'Jl. Sudirman No. 20, Bandung',
                'foto' => null,
            ],
        ];

        foreach ($siswa as $data) {
            Siswa::firstOrCreate([
                'nisn' => $data['nisn'],
            ], $data);
        }
    }
}
