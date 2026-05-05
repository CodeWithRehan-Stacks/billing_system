<?php

namespace App\Services;

use App\Models\Student;
use App\Models\FeeInvoice;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * Generate invoices for all active students in a school for a specific month.
     */
    public function generateMonthlyInvoices($schoolId = null, $month = null, $year = null)
    {
        $month = $month ?? Carbon::now()->format('F');
        $year = $year ?? Carbon::now()->format('Y');
        
        $schools = $schoolId ? School::where('id', $schoolId)->get() : School::all();
        $results = [];

        foreach ($schools as $school) {
            $students = Student::where('school_id', $school->id)
                ->where('status', 'active')
                ->get();

            $generatedCount = 0;
            foreach ($students as $student) {
                // Check if invoice already exists to prevent duplicates
                $exists = FeeInvoice::withoutGlobalScopes()
                    ->where('school_id', $school->id)
                    ->where('student_id', $student->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->exists();

                if (!$exists) {
                    FeeInvoice::create([
                        'school_id' => $school->id,
                        'student_id' => $student->id,
                        'invoice_number' => 'INV-' . $school->id . '-' . date('Ym') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT),
                        'month' => $month,
                        'year' => $year,
                        'issue_date' => Carbon::now(),
                        'due_date' => Carbon::now()->day(10), // Typically due by 10th
                        'base_amount' => $student->monthly_fee,
                        'total_amount' => $student->monthly_fee,
                        'status' => 'pending'
                    ]);
                    $generatedCount++;
                }
            }
            $results[$school->id] = [
                'name' => $school->name,
                'generated' => $generatedCount
            ];
        }

        return $results;
    }

    /**
     * Apply late fees to overdue invoices based on defined rules.
     */
    public function applyLateFees()
    {
        // Rules: 1–5 days late → +5%, 6–10 days late → +10%, 10+ days → +15%
        $overdueInvoices = FeeInvoice::withoutGlobalScopes()
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', Carbon::now()->startOfDay())
            ->get();

        $updatedCount = 0;
        foreach ($overdueInvoices as $invoice) {
            $dueDate = Carbon::parse($invoice->due_date);
            $daysLate = $dueDate->diffInDays(Carbon::now()->startOfDay(), false);

            if ($daysLate <= 0) continue;

            $penaltyPercent = 0;
            if ($daysLate >= 1 && $daysLate <= 5) {
                $penaltyPercent = 0.05;
            } elseif ($daysLate >= 6 && $daysLate <= 10) {
                $penaltyPercent = 0.10;
            } elseif ($daysLate > 10) {
                $penaltyPercent = 0.15;
            }

            if ($penaltyPercent > 0) {
                $lateFee = round($invoice->base_amount * $penaltyPercent, 2);
                
                // Only update if late fee has changed or not yet applied for today
                if ($lateFee != $invoice->late_fee) {
                    $invoice->late_fee = $lateFee;
                    $invoice->total_amount = $invoice->base_amount + $lateFee;
                    $invoice->late_fee_applied = true;
                    $invoice->late_fee_applied_at = Carbon::now();
                    $invoice->status = 'overdue';
                    $invoice->save();
                    $updatedCount++;
                }
            }
        }
        
        return $updatedCount;
    }
}
