<?php

namespace App\Nova\Metrics;

use App\Models\FeePayment;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class RevenueByMonth extends Trend
{
    public function name(): string
    {
        return 'Monthly Revenue Trend';
    }

    public function calculate(NovaRequest $request): \Laravel\Nova\Metrics\TrendResult
    {
        return $this->sumByMonths($request, FeePayment::class, 'amount')
            ->prefix('PKR ');
    }

    public function ranges(): array
    {
        return [
            3  => '3 Months',
            6  => '6 Months',
            12 => '12 Months',
        ];
    }

    public function cacheFor()
    {
        return now()->addMinutes(30);
    }

    public $uriKey = 'revenue-by-month';
}
