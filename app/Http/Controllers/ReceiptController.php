<?php

namespace App\Http\Controllers;

use App\Models\FeeInvoice;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function generateReceipt($invoiceId)
    {
        $invoice = FeeInvoice::with(['student', 'items'])->findOrFail($invoiceId);

        $data = [
            'student' => $invoice->student,
            'invoice' => $invoice,
        ];

        $pdf = Pdf::loadView('receipts.pdf', $data);

        return $pdf->download('invoice-' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }
}
