<?php

namespace App\Services;

use App\Models\FeeInvoice;
use Carbon\Carbon;

class ReportService
{
    /**
     * Get monthly financial summary for a school.
     */
    public function getMonthlySummary(int $schoolId, string $month, string $year): array
    {
        $invoices = FeeInvoice::where('school_id', $schoolId)
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        return [
            'totalInvoiced' => $invoices->sum('total_amount'),
            'totalPaid' => $invoices->sum('paid_amount'),
            'totalUnpaid' => $invoices->whereIn('status', ['pending', 'partial', 'overdue'])->sum('total_amount') - $invoices->sum('paid_amount'),
            'totalOverdue' => $invoices->where('status', 'overdue')->sum('total_amount'),
            'count' => [
                'total' => $invoices->count(),
                'paid' => $invoices->where('status', 'paid')->count(),
                'pending' => $invoices->where('status', 'pending')->count(),
                'overdue' => $invoices->where('status', 'overdue')->count(),
            ],
            'period' => "{$month} {$year}",
        ];
    }
}
