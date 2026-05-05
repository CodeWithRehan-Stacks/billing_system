<?php

namespace App\Nova\Metrics;

use App\Models\FeeInvoice;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class RevenueExpected extends Value
{
    public function name(): string
    {
        return 'Expected Revenue';
    }

    public function calculate(NovaRequest $request): \Laravel\Nova\Metrics\ValueResult
    {
        return $this->sum($request, FeeInvoice::class, 'total_amount')
            ->currency('PKR')
            ->prefix('PKR ');
    }

    public function ranges(): array
    {
        return [
            30 => '30 Days',
            'MTD' => 'Month To Date',
        ];
    }

    public function cacheFor()
    {
        return now()->addMinutes(10);
    }

    public $uriKey = 'revenue-expected';
}
