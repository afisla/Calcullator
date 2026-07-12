<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kantin-alamanah.com'],
            [
                'name'     => 'Admin Kantin Al-Amanah',
                'email'    => 'admin@kantin-alamanah.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );
    }
}
