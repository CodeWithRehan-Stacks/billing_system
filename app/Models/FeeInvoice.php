<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'student_id', 'month', 'year', 'issue_date', 'due_date',
        'total_amount', 'paid_amount', 'late_fee', 'status', 'late_fee_applied'
    ];

    protected $appends = ['remaining_amount'];

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

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
