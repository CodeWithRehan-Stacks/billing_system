<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class UserRoleFilter extends Filter
{
    public $name = 'User Role';

    public function apply(Request $request, $query, $value)
    {
        return $query->where('role', $value);
    }

    public function options(Request $request)
    {
        return [
            'Super Admin' => 'superadmin',
            'Admin' => 'admin',
            'Worker' => 'worker',
        ];
    }
}