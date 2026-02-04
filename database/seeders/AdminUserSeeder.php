<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate([
            'email' => 'superadmin@example.com'
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'user_type' => \App\Enums\UserType::SUPER_ADMIN->value,
        ]);
        $superAdmin->assignRole('admin');

        // Admin
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'user_type' => \App\Enums\UserType::ADMIN->value,
        ]);
        $admin->assignRole('admin');

        // Regular User
        $user = User::firstOrCreate([
            'email' => 'user@example.com'
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'user_type' => \App\Enums\UserType::USER->value,
        ]);
        $user->assignRole('user');
    }
}
