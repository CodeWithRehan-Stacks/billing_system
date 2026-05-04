<?php

namespace App\Nova\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class InvoiceStatusFilter extends Filter
{
    public $name = 'Invoice Status';

    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('status', $value);
    }

    public function options(NovaRequest $request): array
    {
        return [
            'Pending'  => 'pending',
            'Partial'  => 'partial',
            'Paid'     => 'paid',
            'Overdue'  => 'overdue',
        ];
    }
}
