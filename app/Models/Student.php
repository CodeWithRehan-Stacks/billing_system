<?php

namespace App\Models;

use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory, BelongsToSchool;

    protected $fillable = [
        'school_id', 'name', 'father_name', 'mother_name', 'roll_number', 'class', 'section', 
        'phone', 'student_whatsapp', 'father_whatsapp', 'mother_whatsapp', 'address', 
        'admission_date', 'status', 'monthly_fee'
    ];

    public function invoices()
    {
        return $this->hasMany(FeeInvoice::class);
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }
}
