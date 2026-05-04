<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['name' => 'John Doe', 'father_name' => 'Richard Doe', 'class' => 'Class 1', 'section' => 'A'],
            ['name' => 'Jane Smith', 'father_name' => 'Will Smith', 'class' => 'Class 2', 'section' => 'B'],
            ['name' => 'Michael Johnson', 'father_name' => 'Robert Johnson', 'class' => 'Class 3', 'section' => 'A'],
            ['name' => 'Emily Davis', 'father_name' => 'Jim Davis', 'class' => 'Class 4', 'section' => 'C'],
            ['name' => 'William Brown', 'father_name' => 'Dan Brown', 'class' => 'Class 5', 'section' => 'B'],
        ];

        foreach ($students as $data) {
            $data['phone'] = '123-456-' . rand(1000, 9999);
            $data['roll_number'] = 'RN-' . date('Y') . '-' . rand(1000, 9999);
            $data['status'] = 'active';
            Student::create($data);
        }
    }
}

