<?php

namespace App\Nova\Metrics;

use App\Models\FeeInvoice;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class PendingInvoices extends Value
{
    public function name(): string
    {
        return 'Pending Invoices';
    }

    public function calculate(NovaRequest $request): \Laravel\Nova\Metrics\ValueResult
    {
        return $this->count($request, FeeInvoice::where('status', 'pending'));
    }

    public function ranges(): array
    {
        return [];
    }

    public function cacheFor()
    {
        return now()->addMinutes(5);
    }

    public $uriKey = 'pending-invoices';
}
