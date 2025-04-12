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
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Juan',
                'surname' => 'Pérez',
                'password' => Hash::make('admin'),
                'role' => 'admin',
            ]
        );

        // Guest user
        User::updateOrCreate(
            ['email' => 'guest@guest.com'],
            [
                'name' => 'Lucas',
                'surname' => 'González',
                'password' => Hash::make('guest'),
                'role' => 'guest',
            ]
        );
    }
}
