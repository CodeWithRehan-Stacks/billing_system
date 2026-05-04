<?php

namespace App\Http\Controllers;

use App\Models\FeeInvoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    //  Get all invoices
    public function index()
    {
        return response()->json(
            FeeInvoice::latest()->paginate(10)
        );
    }

    // Store new invoice
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string',
            'due_date' => 'required|date',
        ]);

        $invoice = FeeInvoice::create($validated);

        return response()->json([
            'message' => 'Invoice created successfully',
            'data' => $invoice
        ], 201);
    }

    // Show single invoice
    public function show($id)
    {
        $invoice = FeeInvoice::findOrFail($id);

        return response()->json($invoice);
    }

    //  Update invoice
    public function update(Request $request, $id)
    {
        $invoice = FeeInvoice::findOrFail($id);

        $validated = $request->validate([
            'student_id' => 'sometimes|integer',
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string',
            'due_date' => 'sometimes|date',
        ]);

        $invoice->update($validated);

        return response()->json([
            'message' => 'Invoice updated successfully',
            'data' => $invoice
        ]);
    }

}