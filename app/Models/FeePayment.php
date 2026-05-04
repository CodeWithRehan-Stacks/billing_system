<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_invoice_id', 'student_id', 'amount', 'payment_method',
        'payment_date', 'transaction_id', 'notes'
    ];

    public function invoice()
    {
        return $this->belongsTo(FeeInvoice::class, 'fee_invoice_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    protected static function booted()
    {
        static::created(function ($payment) {
            $payment->updateInvoiceStatus();
        });

        static::deleted(function ($payment) {
            $payment->updateInvoiceStatus();
        });
    }

    public function updateInvoiceStatus()
    {
        if ($this->invoice) {
            $totalPaid = $this->invoice->payments()->sum('amount');
            $this->invoice->paid_amount = $totalPaid;
            
            if ($totalPaid >= $this->invoice->total_amount) {
                $this->invoice->status = 'paid';
            } elseif ($totalPaid > 0) {
                $this->invoice->status = 'partial';
            } else {
                // If past due date, might need to be overdue, but let's default to pending if 0
                $this->invoice->status = 'pending';
            }
            
            $this->invoice->save();
        }
    }
}
