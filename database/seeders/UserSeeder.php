<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data role
        $superAdmin = Role::where('name', 'super-admin')->first();
        $admin = Role::where('name', 'admin')->first();

        // Cek apakah role tersedia
        if (!$superAdmin || !$admin) {
            $this->command->error('Role super-admin atau admin tidak ditemukan!');
            $this->command->info('Jalankan php artisan db:seed --class=RoleSeeder terlebih dahulu.');
            return;
        }

        // Buat Super Admin (USER 1)
        User::firstOrCreate(
            ['email' => 'superadmin@alenamandiri.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role_id' => $superAdmin->id,
                'is_active' => true,
                // Field session biarkan null dulu
                'session_id' => null,
                'last_login_at' => null,
                'last_ip' => null,
                'last_user_agent' => null,
            ]
        );

        // Buat Admin (USER 2)
        User::firstOrCreate(
            ['email' => 'admin@alenamandiri.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password'),
                'role_id' => $admin->id,
                'is_active' => true,
                // Field session biarkan null dulu
                'session_id' => null,
                'last_login_at' => null,
                'last_ip' => null,
                'last_user_agent' => null,
            ]
        );

        // BUAT 1 USER KHUSUS TESTING (USER 3)
        User::firstOrCreate(
            ['email' => 'test@alenamandiri.com'],
            [
                'name' => 'User Testing',
                'password' => Hash::make('test123'),
                'role_id' => $admin->id, // atau $superAdmin->id kalau mau super admin
                'is_active' => true,
                'session_id' => null,
                'last_login_at' => null,
                'last_ip' => null,
                'last_user_agent' => null,
            ]
        );
    }
}
