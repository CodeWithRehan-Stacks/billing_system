<?php

namespace App\Http\Controllers;

use App\Models\FeeInvoice;
use Illuminate\Http\Request;

class FeeInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = FeeInvoice::with('student', 'items');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('month')) {
            $query->where('month', $request->month);
        }

        return response()->json($query->paginate(15));
    }

    public function show(FeeInvoice $feeInvoice)
    {
        $feeInvoice->load(['student', 'items', 'payments']);
        return response()->json($feeInvoice);
    }
}
