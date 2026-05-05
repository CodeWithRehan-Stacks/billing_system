<?php

namespace App\Nova\Metrics;

use App\Models\FeeInvoice;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class TotalRevenue extends Value
{
    public function name(): string
    {
        return 'Total Revenue';
    }

    public function calculate(NovaRequest $request): \Laravel\Nova\Metrics\ValueResult
    {
        return $this->sum($request, \App\Models\FeePayment::class, 'amount', 'payment_date')
            ->currency('PKR')
            ->prefix('PKR ');
    }

    public function ranges(): array
    {
        return [
            30 => '30 Days',
            60 => '60 Days',
            365 => '365 Days',
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            'YTD' => 'Year To Date',
        ];
    }

    public function cacheFor()
    {
        return now()->addMinutes(10);
    }

    public $uriKey = 'total-revenue';
}
