<?php

namespace App\Repositories;

use App\Models\FeeInvoice;

class InvoiceRepository
{
    public function getAll($filters = [])
    {
        $query = FeeInvoice::with('student');

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['month'])) {
            $query->where('month', $filters['month']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function findById($id)
    {
        return FeeInvoice::with('student', 'items', 'payments')->findOrFail($id);
    }

    public function create(array $data)
    {
        return FeeInvoice::create($data);
    }

    public function update($id, array $data)
    {
        $invoice = $this->findById($id);
        $invoice->update($data);
        return $invoice;
    }

    public function delete($id)
    {
        $invoice = $this->findById($id);
        return $invoice->delete();
    }
}
