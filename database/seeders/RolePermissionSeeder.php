<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat peran (roles)
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $guru = Role::firstOrCreate(['name' => 'guru']);
        $bendahara = Role::firstOrCreate(['name' => 'bendahara']);

        // Daftar fitur dan operasi CRUD
        $features = [
            'tahun-pelajaran',
            'kurikulum',
            'gtk',
            'siswa',
            'rombel',
            'tabungan-siswa',
            'keuangan-sekolah',
        ];

        $operations = ['create', 'read', 'update', 'delete'];

        $permissions = [];

        // Generate permissions untuk setiap fitur
        foreach ($features as $feature) {
            foreach ($operations as $operation) {
                $permissions[] = "{$operation}-{$feature}";
            }
        }

        // Buat permission di database
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permission ke masing-masing role

        // Admin: full access
        $admin->syncPermissions($permissions);

        // Guru: akses terbatas
        $guruPermissions = [
            'read-tahun-pelajaran',
            'read-kurikulum',
            'read-gtk',
            'read-siswa',
            'read-rombel',
            'read-tabungan-siswa',
            'create-tabungan-siswa',
            'read-keuangan-sekolah',
        ];
        $guru->syncPermissions($guruPermissions);

        // Bendahara: fokus ke tabungan & keuangan
        $bendaharaPermissions = [
            'read-tahun-pelajaran',
            'read-kurikulum',
            'read-gtk',
            'read-siswa',
            'read-rombel',

            // Full akses tabungan siswa
            'create-tabungan-siswa',
            'read-tabungan-siswa',
            'update-tabungan-siswa',
            'delete-tabungan-siswa',

            // Full akses keuangan sekolah
            'create-keuangan-sekolah',
            'read-keuangan-sekolah',
            'update-keuangan-sekolah',
            'delete-keuangan-sekolah',
        ];
        $bendahara->syncPermissions($bendaharaPermissions);
    }
}
