<?php

namespace App\Nova\Metrics;

use App\Models\FeeInvoice;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class OverdueInvoices extends Value
{
    public function name(): string
    {
        return 'Overdue Invoices';
    }

    public function calculate(NovaRequest $request): \Laravel\Nova\Metrics\ValueResult
    {
        return $this->count($request, FeeInvoice::where('status', 'overdue'));
    }

    public function ranges(): array
    {
        return [];
    }

    public function cacheFor()
    {
        return now()->addMinutes(5);
    }

    public $uriKey = 'overdue-invoices';
}
