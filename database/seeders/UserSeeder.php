<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'username' => 'admin',
                'role' => 'admin',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(['email' => $data['email']], [
                'name' => $data['name'],
                'password' => $data['password'],
                'username' => $data['username'],
            ]);

            // Assign role setelah user dibuat
            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            }
        }
    }
}
