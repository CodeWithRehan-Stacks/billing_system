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

        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'user_name' => 'admin',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
        ]);

        for ($i = 1; $i <= 10; $i++) {
            User::factory()->create([
                'first_name' => 'User' . $i,
                'last_name' => 'Test',
                'user_name' => 'user' . $i,
                'email' => "user{$i}@school.com",
                'password' => Hash::make('password'),
            ]);
        }
        // Create 10 normal users
        User::factory(10)->create();

        $this->call([
            FeeStructureSeeder::class,
            StudentSeeder::class,
        ]);
    }
}
