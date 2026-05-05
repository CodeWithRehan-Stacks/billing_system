<?php

namespace App\Policies;

use App\Models\FeePayment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FeePaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, FeePayment $feePayment): bool
    {
        if ($user->isSuperAdmin()) return true;
        return $user->school_id === $feePayment->school_id;
    }

    public function create(User $user): bool
    {
        return $user->isSchoolAdmin() || $user->isWorker();
    }

    public function update(User $user, FeePayment $feePayment): bool
    {
        return ($user->isSchoolAdmin() || $user->isWorker()) && $user->school_id === $feePayment->school_id;
    }

    public function delete(User $user, FeePayment $feePayment): bool
    {
        return $user->isSchoolAdmin() && $user->school_id === $feePayment->school_id;
    }
}
