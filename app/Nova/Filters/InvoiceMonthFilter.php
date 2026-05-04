<?php

namespace App\Nova\Filters;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class InvoiceMonthFilter extends Filter
{
    public $name = 'Month';

    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('month', $value);
    }

    public function options(NovaRequest $request): array
    {
        $options = [];
        // Last 12 months
        for ($i = 0; $i < 12; $i++) {
            $date           = Carbon::now()->subMonths($i);
            $label          = $date->format('F Y');
            $options[$label] = $label;
        }
        return $options;
    }
}
