<?php

namespace App\Policies;

use App\Models\School;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchoolPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isSchoolAdmin();
    }

    public function view(User $user, School $school): bool
    {
        if ($user->isSuperAdmin()) return true;
        return $user->isSchoolAdmin() && $user->school_id === $school->id;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, School $school): bool
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user, School $school): bool
    {
        return $user->isSuperAdmin();
    }
}
