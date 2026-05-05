<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'logo', 'address', 'subdomain', 'status'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function invoices()
    {
        return $this->hasMany(FeeInvoice::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
