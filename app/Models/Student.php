<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'father_name', 'roll_number', 'class', 'section', 
        'phone', 'address', 'admission_date', 'status'
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
