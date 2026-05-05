<?php

namespace App\Services;

use App\Models\FeeInvoice;
use App\Models\School;
use App\Exports\FinancialReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    /**
     * Generate a monthly financial report in Excel or PDF format.
     */
    public function generateMonthlyReport($schoolId, $month, $year, $format = 'excel')
    {
        $school = School::findOrFail($schoolId);
        
        // Ensure directory exists
        if (!Storage::disk('public')->exists('reports')) {
            Storage::disk('public')->makeDirectory('reports');
        }

        if ($format === 'excel') {
            $fileName = "reports/{$school->name}-{$month}-{$year}.xlsx";
            // Clean filename
            $fileName = str_replace(' ', '_', $fileName);
            Excel::store(new FinancialReportExport($schoolId, $month, $year), $fileName, 'public');
            return Storage::disk('public')->url($fileName);
        } else {
            // PDF report
            $invoices = FeeInvoice::withoutGlobalScopes()
                ->where('school_id', $schoolId)
                ->where('month', $month)
                ->where('year', $year)
                ->with('student')
                ->get();

            $summary = [
                'total_invoices' => $invoices->count(),
                'total_amount' => $invoices->sum('total_amount'),
                'total_paid' => $invoices->sum('paid_amount'),
                'total_unpaid' => $invoices->sum('total_amount') - $invoices->sum('paid_amount'),
                'overdue_count' => $invoices->where('status', 'overdue')->count(),
            ];

            $pdf = Pdf::loadView('pdfs.financial_report', [
                'school' => $school,
                'month' => $month,
                'year' => $year,
                'invoices' => $invoices,
                'summary' => $summary
            ]);

            $fileName = "reports/{$school->name}-{$month}-{$year}.pdf";
            $fileName = str_replace(' ', '_', $fileName);
            Storage::disk('public')->put($fileName, $pdf->output());
            return Storage::disk('public')->url($fileName);
        }
    }
}
