<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('roll_number')->unique();
            $table->string('class')->nullable();
            $table->string('section')->nullable();
            $table->string('phone')->nullable();
            $table->string('student_whatsapp')->nullable();
            $table->string('father_whatsapp')->nullable();
            $table->string('mother_whatsapp')->nullable();
            $table->text('address')->nullable();
            $table->date('admission_date')->nullable();
            $table->decimal('monthly_fee', 10, 2)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
