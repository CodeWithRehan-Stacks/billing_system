<?php

namespace App\Exports;

use App\Models\FeeInvoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $schoolId;
    protected $month;
    protected $year;

    public function __construct($schoolId, $month, $year)
    {
        $this->schoolId = $schoolId;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return FeeInvoice::withoutGlobalScopes()
            ->where('school_id', $this->schoolId)
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->with('student')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Invoice #',
            'Student Name',
            'Roll Number',
            'Class',
            'Base Amount',
            'Late Fee',
            'Total Amount',
            'Paid Amount',
            'Status'
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->invoice_number,
            $invoice->student->name,
            $invoice->student->roll_number,
            $invoice->student->class,
            $invoice->base_amount,
            $invoice->late_fee,
            $invoice->total_amount,
            $invoice->paid_amount,
            ucfirst($invoice->status)
        ];
    }
}
