<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isSchoolAdmin();
    }

    public function view(User $user, User $model): bool
    {
        if ($user->isSuperAdmin()) return true;
        if ($user->isSchoolAdmin()) {
            return $user->school_id === $model->school_id && $model->role === 'worker';
        }
        return $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isSchoolAdmin();
    }

    public function update(User $user, User $model): bool
    {
        if ($user->isSuperAdmin()) return true;
        if ($user->isSchoolAdmin()) {
            return $user->school_id === $model->school_id && $model->role === 'worker';
        }
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->isSuperAdmin()) return true;
        if ($user->isSchoolAdmin()) {
            return $user->school_id === $model->school_id && $model->role === 'worker';
        }
        return false;
    }
}
