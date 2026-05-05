<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\School;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a default school
        $school = School::create([
            'name' => 'Green Valley Academy',
            'subdomain' => 'greenvalley',
            'address' => '123 Education St, Lahore',
            'status' => 'active'
        ]);

        // Create Admin User for this school
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'user_name' => 'admin',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'school_id' => $school->id,
            'role' => 'school_admin'
        ]);

        // Create some more users for this school
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'first_name' => 'User' . $i,
                'last_name' => 'Staff',
                'user_name' => 'user' . $i,
                'email' => "user{$i}@school.com",
                'password' => Hash::make('password'),
                'school_id' => $school->id,
                'role' => 'accountant'
            ]);
        }

        $this->call([
            StudentSeeder::class,
        ]);
    }
}
