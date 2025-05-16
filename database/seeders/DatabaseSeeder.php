<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // PermissionGroupSeeder::class,
            AplikasiSeeder::class,
            RolePermissionSeeder::class,
            AgamaSeeder::class,
            JenisKelaminSeeder::class,
            PendidikanSeeder::class,
            PekerjaanSeeder::class,
            HobiSeeder::class,
            CitaCitaSeeder::class,
            JarakSeeder::class,
            TinggalSeeder::class,
            SemesterSeeder::class,
            TahunPelajaranSeeder::class,
            KurikulumSeeder::class,
            KelasSeeder::class,
            UserSeeder::class,
            KewarganegaraanSeeder::class,
            SekolahSeeder::class,
        ]);
    }
}
