<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionGroups = [
            [
                'name' => 'Dashboard'
            ],
            [
                'name' => 'Tahun Pelajaran'
            ],
            [
                'name' => 'Kurikulum'
            ],
            [
                'name' => 'GTK'
            ],
            [
                'name' => 'Kelas'
            ],
            [
                'name' => 'Siswa'
            ],
            [
                'name' => 'Rombel'
            ],
            [
                'name' => 'Tabungan'
            ],
            [
                'name' => 'Keuangan'
            ],
            [
                'name' => 'User'
            ],
            [
                'name' => 'Role'
            ],
            [
                'name' => 'Permission'
            ],
            [
                'name' => 'Group Permission'
            ],
            [
                'name' => 'Madrasah'
            ],
            [
                'name' => 'Aplikasi'
            ],
        ];

        foreach ($permissionGroups as $permission) {
            $permissionGroup = new PermissionGroup();
            $permissionGroup->name = $permission['name'];
            $permissionGroup->save();
        }
    }
}
