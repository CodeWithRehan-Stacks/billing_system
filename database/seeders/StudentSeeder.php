<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\School;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        if (!$school) return;

        $students = [
            ['name' => 'John Doe', 'father_name' => 'Richard Doe', 'mother_name' => 'Mary Doe', 'class' => 'Class 1', 'section' => 'A', 'monthly_fee' => 5000],
            ['name' => 'Jane Smith', 'father_name' => 'Will Smith', 'mother_name' => 'Jada Smith', 'class' => 'Class 2', 'section' => 'B', 'monthly_fee' => 6000],
            ['name' => 'Michael Johnson', 'father_name' => 'Robert Johnson', 'mother_name' => 'Linda Johnson', 'class' => 'Class 3', 'section' => 'A', 'monthly_fee' => 5500],
            ['name' => 'Emily Davis', 'father_name' => 'Jim Davis', 'mother_name' => 'Sarah Davis', 'class' => 'Class 4', 'section' => 'C', 'monthly_fee' => 5200],
            ['name' => 'William Brown', 'father_name' => 'Dan Brown', 'mother_name' => 'Alice Brown', 'class' => 'Class 5', 'section' => 'B', 'monthly_fee' => 5800],
        ];

        foreach ($students as $data) {
            $data['school_id'] = $school->id;
            $data['phone'] = '0300' . rand(1000000, 9999999);
            $data['father_whatsapp'] = $data['phone'];
            $data['roll_number'] = 'RN-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $data['status'] = 'active';
            $data['admission_date'] = now()->subMonths(rand(1, 12));
            Student::create($data);
        }
    }
}
