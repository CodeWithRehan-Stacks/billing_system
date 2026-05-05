<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Filtered by indexQuery
    }

    public function view(User $user, Student $student): bool
    {
        if ($user->isSuperAdmin()) return true;
        return $user->school_id === $student->school_id;
    }

    public function create(User $user): bool
    {
        return $user->isSchoolAdmin() || $user->isWorker();
    }

    public function update(User $user, Student $student): bool
    {
        return ($user->isSchoolAdmin() || $user->isWorker()) && $user->school_id === $student->school_id;
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->isSchoolAdmin() && $user->school_id === $student->school_id;
    }
}
