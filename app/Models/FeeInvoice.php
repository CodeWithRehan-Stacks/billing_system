<?php

namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeInvoice extends Model
{
    use HasFactory, BelongsToSchool;

    protected $fillable = [
        'school_id', 'invoice_number', 'student_id', 'month', 'year', 'issue_date', 'due_date',
        'base_amount', 'total_amount', 'paid_amount', 'late_fee', 'status', 'late_fee_applied', 'late_fee_applied_at'
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

    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }
}
