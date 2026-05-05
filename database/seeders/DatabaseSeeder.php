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
        // 1. Create Super Admin
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'user_name' => 'superadmin',
            'email' => 'super@system.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'school_id' => null, // Super admins aren't tied to a specific school
        ]);

        // 2. Create Schools
        $school1 = School::create([
            'name' => 'Green Valley Academy',
            'subdomain' => 'greenvalley',
            'address' => '123 Education St, Lahore',
            'status' => 'active'
        ]);

        $school2 = School::create([
            'name' => 'Beacon House School',
            'subdomain' => 'beacon',
            'address' => '456 Knowledge Ave, Karachi',
            'status' => 'active'
        ]);

        // 3. Create School Admins
        User::create([
            'first_name' => 'GV',
            'last_name' => 'Admin',
            'user_name' => 'gvadmin',
            'email' => 'admin@greenvalley.com',
            'password' => Hash::make('password'),
            'school_id' => $school1->id,
            'role' => 'school_admin'
        ]);

        User::create([
            'first_name' => 'Beacon',
            'last_name' => 'Admin',
            'user_name' => 'beaconadmin',
            'email' => 'admin@beacon.com',
            'password' => Hash::make('password'),
            'school_id' => $school2->id,
            'role' => 'school_admin'
        ]);

        // 4. Create School Workers
        User::create([
            'first_name' => 'GV',
            'last_name' => 'Worker',
            'user_name' => 'gvworker',
            'email' => 'worker@greenvalley.com',
            'password' => Hash::make('password'),
            'school_id' => $school1->id,
            'role' => 'worker'
        ]);

        User::create([
            'first_name' => 'Beacon',
            'last_name' => 'Worker',
            'user_name' => 'beaconworker',
            'email' => 'worker@beacon.com',
            'password' => Hash::make('password'),
            'school_id' => $school2->id,
            'role' => 'worker'
        ]);

        $this->call([
            StudentSeeder::class,
        ]);
    }
}
