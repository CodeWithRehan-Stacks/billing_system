<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'user_name',
        'email',
        'password',
        'date_of_birth',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'admin-token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Virtual "name" attribute required by Laravel Nova.
     * Nova uses $title = 'name' internally for user display.
     */
    public function getNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Setting "name" splits it back to first/last name.
     */
    public function setNameAttribute(string $value): void
    {
        $parts = explode(' ', $value, 2);
        $this->attributes['first_name'] = $parts[0] ?? '';
        $this->attributes['last_name']  = $parts[1] ?? '';
    }
}
