<?php

namespace App\Policies;

use App\Models\ErrorLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ErrorLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function view(User $user, ErrorLog $errorLog): bool
    {
        return $user->isSuperAdmin();
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ErrorLog $errorLog): bool
    {
        return false;
    }

    public function delete(User $user, ErrorLog $errorLog): bool
    {
        return $user->isSuperAdmin();
    }
}
