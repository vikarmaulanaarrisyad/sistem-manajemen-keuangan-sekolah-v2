<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sekolah;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sekolah::firstOrCreate(
            ['npsn' => 22334455], // Cek berdasarkan NPSN agar tidak duplikat
            [
                'nama' => 'Madrasah Ibtidaiyah Kemanggungan',
                'nsm' => 99887766,
                'alamat' => 'Jl. Santri No. 20, Surabaya',
                'notelp' => 6289876543210,
                'email' => 'info@mtsnurulhuda.ac.id',
                'website' => 'https://mtsnurulhuda.ac.id',
                'kepala_sekolah_id' => 1,
                'bendahara_id' => 1,
            ]
        );
    }
}
