<?php

namespace App\Policies;

use App\Models\FeeInvoice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FeeInvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, FeeInvoice $feeInvoice): bool
    {
        if ($user->isSuperAdmin()) return true;
        return $user->school_id === $feeInvoice->school_id;
    }

    public function create(User $user): bool
    {
        return $user->isSchoolAdmin() || $user->isWorker();
    }

    public function update(User $user, FeeInvoice $feeInvoice): bool
    {
        return ($user->isSchoolAdmin() || $user->isWorker()) && $user->school_id === $feeInvoice->school_id;
    }

    public function delete(User $user, FeeInvoice $feeInvoice): bool
    {
        return $user->isSchoolAdmin() && $user->school_id === $feeInvoice->school_id;
    }
}
