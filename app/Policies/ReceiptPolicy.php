<?php

namespace App\Policies;

use App\Models\Receipt;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReceiptPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Receipt $receipt): bool
    {
        if ($user->isSuperAdmin()) return true;
        return $user->school_id === $receipt->school_id;
    }

    public function create(User $user): bool
    {
        return false; // Automatically generated
    }

    public function update(User $user, Receipt $receipt): bool
    {
        return false; // Should not be updated manually
    }

    public function delete(User $user, Receipt $receipt): bool
    {
        return $user->isSchoolAdmin() && $user->school_id === $receipt->school_id;
    }
}
