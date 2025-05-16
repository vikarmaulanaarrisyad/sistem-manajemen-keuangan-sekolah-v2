<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $guru = Role::firstOrCreate(['name' => 'guru']);
        $bendahara = Role::firstOrCreate(['name' => 'bendahara']);

        // Mapping fitur ke nama group permission
        $featureGroups = [
            'tahun-pelajaran'   => 'Tahun Pelajaran',
            'kurikulum'         => 'Kurikulum',
            'gtk'               => 'GTK',
            'kelas'               => 'Kelas',
            'siswa'             => 'Siswa',
            'rombel'            => 'Rombel',
            'tabungan-siswa'    => 'Tabungan',
            'keuangan-sekolah'  => 'Keuangan',
        ];

        $operations = ['create', 'read', 'update', 'delete'];
        $permissions = [];

        foreach ($featureGroups as $feature => $groupName) {
            $group = PermissionGroup::where('name', $groupName)->first();

            if (!$group) {
                // Jika group belum ada, skip atau buat baru (optional)
                $group = PermissionGroup::create(['name' => $groupName]);
            }

            foreach ($operations as $operation) {
                $name = "{$operation}-{$feature}";

                $permission = Permission::firstOrCreate(
                    ['name' => $name],
                    ['permission_group_id' => $group->id]
                );

                // Update jika belum disetel permission_group_id-nya
                if ($permission->permission_group_id !== $group->id) {
                    $permission->permission_group_id = $group->id;
                    $permission->save();
                }

                $permissions[] = $permission->name;
            }
        }

        // Assign permissions to roles
        $admin->syncPermissions($permissions);

        $guruPermissions = [
            'read-tabungan-siswa',
            'create-tabungan-siswa',
            'read-keuangan-sekolah',
        ];
        $guru->syncPermissions($guruPermissions);

        $bendaharaPermissions = [
            'create-keuangan-sekolah',
            'read-keuangan-sekolah',
            'update-keuangan-sekolah',
            'delete-keuangan-sekolah',
        ];
        $bendahara->syncPermissions($bendaharaPermissions);
    }
}
