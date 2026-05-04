<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\InvoiceRepository;
use Illuminate\Http\Request;

class FeeInvoiceController extends Controller
{
    protected $invoiceRepo;

    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->invoiceRepo = $invoiceRepo;
    }

    public function index(Request $request)
    {
        return response()->json($this->invoiceRepo->getAll($request->all()));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'month' => 'required|string',
            'year' => 'required|string',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $validated['invoice_number'] = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        $invoice = $this->invoiceRepo->create($validated);

        return response()->json(['message' => 'Invoice created successfully', 'data' => $invoice], 201);
    }

    public function show($id)
    {
        return response()->json($this->invoiceRepo->findById($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'sometimes|required|in:pending,partial,paid,overdue',
            'late_fee' => 'sometimes|numeric|min:0',
            'paid_amount' => 'sometimes|numeric|min:0'
        ]);

        $invoice = $this->invoiceRepo->update($id, $validated);

        return response()->json(['message' => 'Invoice updated successfully', 'data' => $invoice]);
    }

    public function destroy($id)
    {
        $this->invoiceRepo->delete($id);
        return response()->json(['message' => 'Invoice deleted successfully']);
    }
}
