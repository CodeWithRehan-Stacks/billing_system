<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = [
        'message', 'level', 'file', 'line', 'stack_trace', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
