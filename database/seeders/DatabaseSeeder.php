<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // updateOrCreate artinya: Cek dulu apakah ID 1 sudah ada. 
        // Jika BELUM ada, buat baru. Jika SUDAH ada, perbarui saja datanya (Aman dari error duplicate!)
        User::updateOrCreate(
            ['id' => 1], // Kunci pengecekan
            [
                'name' => 'Developer',
                'email' => 'developer@gmail.com',
                'password' => Hash::make('password'),
            ]
        );
    }
}