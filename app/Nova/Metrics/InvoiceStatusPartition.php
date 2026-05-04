<?php

namespace App\Nova\Metrics;

use App\Models\FeeInvoice;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class InvoiceStatusPartition extends Partition
{
    public function name(): string
    {
        return 'Invoices by Status';
    }

    public function calculate(NovaRequest $request): \Laravel\Nova\Metrics\PartitionResult
    {
        return $this->count($request, FeeInvoice::class, 'status')
            ->label(fn ($value) => match ($value) {
                'pending' => 'Pending',
                'partial' => 'Partial',
                'paid'    => 'Paid',
                'overdue' => 'Overdue',
                default   => ucfirst($value),
            })
            ->colors([
                'pending' => '#f59e0b',
                'partial' => '#3b82f6',
                'paid'    => '#10b981',
                'overdue' => '#ef4444',
            ]);
    }

    public function cacheFor()
    {
        return now()->addMinutes(10);
    }

    public $uriKey = 'invoice-status-partition';
}
