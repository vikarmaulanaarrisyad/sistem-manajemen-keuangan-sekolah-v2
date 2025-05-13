<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pekerjaan; // Pastikan model sudah dibuat

class PekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pekerjaanList = [
            'Tidak Bekerja',
            'PNS',
            'TNI/Polri',
            'Pegawai Swasta',
            'Wirausaha',
            'Petani',
            'Nelayan',
            'Buruh',
            'Ibu Rumah Tangga'
        ];

        foreach ($pekerjaanList as $pekerjaan) {
            Pekerjaan::firstOrCreate(['nama' => $pekerjaan]);
        }
    }
}
