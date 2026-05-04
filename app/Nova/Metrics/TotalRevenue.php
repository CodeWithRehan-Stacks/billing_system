<?php

namespace App\Nova\Metrics;

use App\Models\FeeInvoice;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class TotalRevenue extends Value
{
    public function name(): string
    {
        return 'Total Revenue (This Month)';
    }

    public function calculate(NovaRequest $request): \Laravel\Nova\Metrics\ValueResult
    {
        $total = FeeInvoice::whereMonth('issue_date', now()->month)
            ->whereYear('issue_date', now()->year)
            ->sum('paid_amount');

        return $this->result($total)->currency('PKR')->prefix('PKR ');
    }

    public function ranges(): array
    {
        return [];
    }

    public function cacheFor()
    {
        return now()->addMinutes(10);
    }

    public $uriKey = 'total-revenue';
}
