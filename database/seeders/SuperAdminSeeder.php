<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin Utama',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'status' => 'active',
                'last_login_at' => null,
            ]
        );
    }
}
