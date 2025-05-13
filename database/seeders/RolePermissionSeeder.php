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

        // Buat daftar izin (permissions)
        $permissions = [
            'manage-tahun-pelajaran',
            'manage-siswa',
            'manage-kurikulum',
            'manage-mata-pelajaran',
            'manage-kelas'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Berikan izin kepada admin
        $admin->givePermissionTo($permissions);
    }
}
