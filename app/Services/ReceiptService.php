<?php

namespace App\Services;

use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReceiptService
{
    /**
     * Generate a professional PDF receipt for a payment.
     */
    public function generateReceipt(FeePayment $payment)
    {
        $invoice = $payment->invoice;
        $school = $invoice->school;

        $receiptNumber = 'REC-' . $school->id . '-' . date('Ymd') . '-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT);

        $receipt = Receipt::create([
            'school_id' => $school->id,
            'fee_invoice_id' => $invoice->id,
            'receipt_number' => $receiptNumber,
            'file_path' => 'temporary', // Placeholder
            'generated_at' => Carbon::now(),
        ]);

        $pdf = Pdf::loadView('pdfs.receipt', [
            'receipt' => $receipt,
            'payment' => $payment
        ]);

        $fileName = 'receipts/' . $receiptNumber . '.pdf';
        
        // Ensure directory exists
        if (!Storage::disk('public')->exists('receipts')) {
            Storage::disk('public')->makeDirectory('receipts');
        }

        Storage::disk('public')->put($fileName, $pdf->output());

        $receipt->update(['file_path' => $fileName]);

        return $receipt;
    }
}
