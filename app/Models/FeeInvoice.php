<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'student_id', 'month', 'issue_date', 'due_date',
        'total_amount', 'paid_amount', 'discount', 'late_fee', 'status',
        'late_fee_type', 'late_fee_value', 'late_fee_applied'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }
}
