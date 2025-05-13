<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kewarganegaraan;

class KewarganegaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Warga Negara Indonesia (WNI)'],
            ['nama' => 'Warga Negara Asing (WNA)'],
        ];

        foreach ($data as $item) {
            Kewarganegaraan::firstOrCreate(['nama' => $item['nama']], $item);
        }
    }
}
