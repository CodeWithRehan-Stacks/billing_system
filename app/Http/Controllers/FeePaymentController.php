<?php

namespace App\Http\Controllers;

use App\Models\FeeInvoice;
use App\Models\FeePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeePaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = FeePayment::with('student', 'invoice');
        
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fee_invoice_id' => 'required|exists:fee_invoices,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,bank_transfer,online',
            'payment_date' => 'nullable|date',
            'transaction_id' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $invoice = FeeInvoice::findOrFail($validated['fee_invoice_id']);

        // Check if paying more than remaining
        $remaining = $invoice->total_amount - $invoice->paid_amount;
        if ($validated['amount'] > $remaining) {
            return response()->json(['message' => 'Payment amount exceeds remaining balance.'], 400);
        }

        $payment = null;

        DB::transaction(function () use ($validated, $invoice, &$payment) {
            $paymentDate = $validated['payment_date'] ?? Carbon::now();
            
            $payment = FeePayment::create([
                'fee_invoice_id' => $invoice->id,
                'student_id' => $invoice->student_id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => $paymentDate,
                'transaction_id' => $validated['transaction_id'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $invoice->paid_amount += $validated['amount'];
            
            if ($invoice->paid_amount >= $invoice->total_amount) {
                $invoice->status = 'paid';
            } else {
                $invoice->status = 'partial';
            }
            
            $invoice->save();
        });

        return response()->json(['message' => 'Payment recorded successfully', 'data' => $payment], 201);
    }
}
