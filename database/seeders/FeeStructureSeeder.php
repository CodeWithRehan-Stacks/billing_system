<?php

namespace Database\Seeders;

use App\Models\FeeStructure;
use Illuminate\Database\Seeder;

class FeeStructureSeeder extends Seeder
{
    public function run(): void
    {
        $classes = ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'];
        
        foreach ($classes as $class) {
            FeeStructure::create(['class' => $class, 'fee_type' => 'Tuition Fee', 'amount' => 2500]);
            FeeStructure::create(['class' => $class, 'fee_type' => 'Library Fee', 'amount' => 500]);
            FeeStructure::create(['class' => $class, 'fee_type' => 'Lab Fee', 'amount' => 1000]);
        }
    }
}

