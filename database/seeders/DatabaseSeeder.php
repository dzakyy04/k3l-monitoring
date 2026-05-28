<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'supervisor@example.com'],
            [
                'name' => 'Supervisor',
                'password' => Hash::make('password'),
                'role' => 'supervisor',
            ]
        );

        User::firstOrCreate(
            ['email' => 'petugas@example.com'],
            [
                'name' => 'Petugas',
                'password' => Hash::make('password'),
                'role' => 'petugas',
            ]
        );
    }
}
