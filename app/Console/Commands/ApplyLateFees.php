<?php

namespace App\Console\Commands;

use App\Models\FeeInvoice;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ApplyLateFees extends Command
{
    protected $signature = 'invoices:apply-late-fees';
    protected $description = 'Daily check to apply late fee penalties to overdue invoices';

    public function handle()
    {
        $this->info('Checking for overdue invoices...');

        $today = Carbon::today();

        // Find all pending or partial invoices that are past their due date
        $invoices = FeeInvoice::whereIn('status', ['pending', 'partial'])
            ->where('due_date', '<', $today->toDateString())
            ->get();

        foreach ($invoices as $invoice) {
            $dueDate = Carbon::parse($invoice->due_date);
            $daysLate = $today->diffInDays($dueDate);

            $penaltyRate = 0;
            if ($daysLate >= 1 && $daysLate <= 5) {
                $penaltyRate = 0.05; // 5%
            } elseif ($daysLate >= 6 && $daysLate <= 10) {
                $penaltyRate = 0.10; // 10%
            } elseif ($daysLate > 10) {
                $penaltyRate = 0.15; // 15%
            }

            if ($penaltyRate > 0) {
                $lateFee = $invoice->base_amount * $penaltyRate;
                
                // Only update if the calculated late fee is higher than existing (to avoid double charging same rate)
                // Or update daily if that's the logic. User said: 1-5 days late -> +5%.
                // Usually this means it's a one-time jump at each threshold.
                
                if ($invoice->late_fee < $lateFee) {
                    $invoice->update([
                        'late_fee' => $lateFee,
                        'total_amount' => $invoice->base_amount + $lateFee,
                        'status' => 'overdue',
                        'late_fee_applied' => true,
                        'late_fee_applied_at' => now(),
                    ]);

                    $this->line(" - Applied {$penaltyRate}% penalty to invoice {$invoice->invoice_number}");
                }
            }
        }

        $this->info('Late fee application completed.');
    }
}
