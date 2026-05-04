<?php

namespace App\Nova\Metrics;

use App\Models\FeeInvoice;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class TotalInvoices extends Value
{
    public function name(): string
    {
        return 'Total Invoices';
    }

    public function calculate(NovaRequest $request): \Laravel\Nova\Metrics\ValueResult
    {
        return $this->count($request, FeeInvoice::class);
    }

    public function ranges(): array
    {
        return [
            30  => '30 Days',
            60  => '60 Days',
            365 => '1 Year',
            'MTD' => 'Month to Date',
            'YTD' => 'Year to Date',
        ];
    }

    public function cacheFor()
    {
        return now()->addMinutes(10);
    }

    public $uriKey = 'total-invoices';
}
