<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hobi; // Pastikan model sudah dibuat

class HobiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hobiList = [
            'Membaca',
            'Menulis',
            'Olahraga',
            'Memasak',
            'Melukis',
            'Fotografi',
            'Bermain Musik',
            'Menari',
            'Berkebun',
            'Travelling'
        ];

        foreach ($hobiList as $hobi) {
            Hobi::firstOrCreate(['nama' => $hobi]);
        }
    }
}
