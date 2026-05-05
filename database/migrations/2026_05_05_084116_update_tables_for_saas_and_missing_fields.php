<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add school_id to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('set null');
            $table->string('role')->default('school_admin'); // super_admin, school_admin, accountant
        });

        // Add school_id and missing fields to students
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('school_id')->after('id')->nullable()->constrained('schools')->onDelete('cascade');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->string('student_whatsapp')->nullable()->after('phone');
            $table->string('father_whatsapp')->nullable()->after('student_whatsapp');
            $table->string('mother_whatsapp')->nullable()->after('father_whatsapp');
            $table->decimal('monthly_fee', 10, 2)->default(0)->after('status');
        });

        // Add school_id to fee_invoices and late fee tracking
        Schema::table('fee_invoices', function (Blueprint $table) {
            $table->foreignId('school_id')->after('id')->nullable()->constrained('schools')->onDelete('cascade');
            $table->decimal('base_amount', 10, 2)->after('due_date')->nullable();
            $table->timestamp('late_fee_applied_at')->nullable()->after('late_fee_applied');
        });

        // Add school_id to fee_payments
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->foreignId('school_id')->after('id')->nullable()->constrained('schools')->onDelete('cascade');
        });

        // Create receipts table
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('fee_invoice_id')->constrained('fee_invoices')->onDelete('cascade');
            $table->string('receipt_number')->unique();
            $table->string('file_path');
            $table->timestamp('generated_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');

        Schema::table('fee_payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('school_id');
        });

        Schema::table('fee_invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('school_id');
            $table->dropColumn(['base_amount', 'late_fee_applied_at']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropConstrainedForeignId('school_id');
            $table->dropColumn(['mother_name', 'student_whatsapp', 'father_whatsapp', 'mother_whatsapp', 'monthly_fee']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('school_id');
            $table->dropColumn('role');
        });
    }
};
