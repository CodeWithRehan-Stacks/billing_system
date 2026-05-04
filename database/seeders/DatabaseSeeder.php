<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin
        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'user_name' => 'admin',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
        ]);

        $this->call([
            FeeStructureSeeder::class,
            StudentSeeder::class,
        ]);
    }
}
