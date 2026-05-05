<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\School;
use App\Models\FeeInvoice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $schools = School::all();
        if ($schools->isEmpty()) return;

        foreach ($schools as $school) {
            for ($i = 1; $i <= 50; $i++) {
                $student = Student::create([
                    'school_id' => $school->id,
                    'name' => "Student {$i} (" . ucfirst($school->subdomain) . ")",
                    'father_name' => "Father of Student {$i}",
                    'mother_name' => "Mother of Student {$i}",
                    'roll_number' => strtoupper($school->subdomain) . "-" . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'class' => 'Grade ' . rand(1, 10),
                    'section' => chr(rand(65, 68)), // A, B, C, D
                    'phone' => '0300' . rand(1000000, 9999999),
                    'father_whatsapp' => '0300' . rand(1000000, 9999999),
                    'status' => 'active',
                    'admission_date' => now()->subMonths(rand(1, 24)),
                    'monthly_fee' => rand(3000, 10000),
                    'address' => 'Sample Address ' . $i,
                ]);

                // Create some invoices for the first 5 students of each school (total 10 invoices)
                if ($i <= 5) {
                    FeeInvoice::create([
                        'school_id' => $school->id,
                        'student_id' => $student->id,
                        'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                        'month' => now()->format('F'),
                        'year' => now()->format('Y'),
                        'issue_date' => now()->startOfMonth(),
                        'due_date' => now()->startOfMonth()->addDays(10),
                        'base_amount' => $student->monthly_fee,
                        'total_amount' => $student->monthly_fee,
                        'status' => 'pending',
                        'late_fee_applied' => false,
                    ]);
                }
            }
        }
    }
}
