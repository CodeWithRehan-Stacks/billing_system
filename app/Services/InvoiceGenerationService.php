<?php

namespace App\Services;

use App\Models\Student;
use App\Models\FeeStructure;
use App\Models\FeeInvoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class InvoiceGenerationService
{
    public function generateMonthlyInvoices()
    {
        $currentMonth = Carbon::now()->format('F Y');
        $issueDate = Carbon::now()->startOfMonth();
        $dueDate = Carbon::now()->startOfMonth()->addDays(10); // Due 10th of every month

        $activeStudents = Student::where('status', 'active')->get();

        foreach ($activeStudents as $student) {
            // Prevent duplicate invoice for the month
            $exists = FeeInvoice::where('student_id', $student->id)
                ->where('month', $currentMonth)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::transaction(function () use ($student, $currentMonth, $issueDate, $dueDate) {
                $feeStructures = FeeStructure::where('class', $student->class)->get();
                $totalAmount = $feeStructures->sum('amount');

                if ($totalAmount <= 0) {
                    return; // No fees defined for this class
                }

                $invoice = FeeInvoice::create([
                    'invoice_number' => 'INV-' . date('Ymd') . '-' . Str::upper(Str::random(5)),
                    'student_id' => $student->id,
                    'month' => $currentMonth,
                    'issue_date' => $issueDate,
                    'due_date' => $dueDate,
                    'total_amount' => $totalAmount,
                    'paid_amount' => 0,
                    'status' => 'pending',
                ]);

                foreach ($feeStructures as $fee) {
                    InvoiceItem::create([
                        'fee_invoice_id' => $invoice->id,
                        'description' => $fee->fee_type,
                        'amount' => $fee->amount,
                    ]);
                }
            });
        }
    }

    public function applyLateFees()
    {
        // Example logic for late fee
        $overdueInvoices = FeeInvoice::whereIn('status', ['pending', 'partial'])
            ->where('due_date', '<', Carbon::now()->toDateString())
            ->where('late_fee_applied', false)
            ->get();

        foreach ($overdueInvoices as $invoice) {
            // Apply 5% late fee
            $lateFee = $invoice->total_amount * 0.05;
            
            $invoice->update([
                'status' => 'overdue',
                'late_fee' => $lateFee,
                'late_fee_type' => 'percentage',
                'late_fee_value' => 5,
                'late_fee_applied' => true,
                'total_amount' => $invoice->total_amount + $lateFee
            ]);
        }
    }
}
