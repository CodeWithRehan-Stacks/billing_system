<?php

namespace App\Services;

use App\Models\FeeInvoice;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReceiptService
{
    /**
     * Generate a professional PDF receipt for a paid invoice.
     */
    public function generateForInvoice(FeeInvoice $invoice): Receipt
    {
        // Load the view with invoice data
        $pdf = Pdf::loadView('pdf.receipt', [
            'invoice' => $invoice,
            'school' => $invoice->school,
            'student' => $invoice->student,
            'payment' => $invoice->payments()->latest()->first(),
        ]);

        // Define file name and path
        $fileName = 'receipt_' . $invoice->invoice_number . '_' . time() . '.pdf';
        $filePath = 'receipts/' . $fileName;

        // Store PDF in local/S3 storage
        Storage::disk('public')->put($filePath, $pdf->output());

        // Create receipt record
        return Receipt::create([
            'school_id' => $invoice->school_id,
            'fee_invoice_id' => $invoice->id,
            'receipt_number' => 'REC-' . strtoupper(Str::random(8)),
            'file_path' => $filePath,
            'generated_at' => now(),
        ]);
    }
}
