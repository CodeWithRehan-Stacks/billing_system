<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('month'); 
            $table->string('year'); 
            $table->date('issue_date');
            $table->date('due_date');
            $table->decimal('base_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('late_fee', 10, 2)->default(0);
            $table->string('status')->default('pending'); // pending, sent, partial, paid, overdue
            $table->boolean('late_fee_applied')->default(false);
            $table->timestamp('late_fee_applied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_invoices');
    }
};
