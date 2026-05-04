<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function generateReceipt($paymentId)
    {
        $payment = FeePayment::with(['invoice.student', 'invoice.items'])->findOrFail($paymentId);

        $data = [
            'payment' => $payment,
            'student' => $payment->invoice->student,
            'invoice' => $payment->invoice,
        ];

        $pdf = Pdf::loadView('receipts.pdf', $data);

        return $pdf->download('receipt-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }
}
