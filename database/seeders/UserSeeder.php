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

        // Buat Super Admin
        User::firstOrCreate(
            ['email' => 'superadmin@alenamandiri.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role_id' => $superAdmin->id,
                'is_active' => true,
            ]
        );

        // Buat Admin
        User::firstOrCreate(
            ['email' => 'admin@alenamandiri.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password'),
                'role_id' => $admin->id,
                'is_active' => true,
            ]
        );
    }
}
