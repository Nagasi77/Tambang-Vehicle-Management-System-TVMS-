<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@tvms.com'],
            [
                'name'     => 'Admin TVMS',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        // Approver 1
        User::updateOrCreate(
            ['email' => 'approver1@tvms.com'],
            [
                'name'     => 'Approver Satu',
                'password' => Hash::make('password123'),
                'role'     => 'approver',
            ]
        );

        // Approver 2
        User::updateOrCreate(
            ['email' => 'approver2@tvms.com'],
            [
                'name'     => 'Approver Dua',
                'password' => Hash::make('password123'),
                'role'     => 'approver',
            ]
        );
    }
}
