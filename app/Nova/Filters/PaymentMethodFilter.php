<?php

namespace App\Nova\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class PaymentMethodFilter extends Filter
{
    public $name = 'Payment Method';

    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('payment_method', $value);
    }

    public function options(NovaRequest $request): array
    {
        return [
            'Cash'          => 'cash',
            'Bank Transfer' => 'bank_transfer',
            'Online'        => 'online',
        ];
    }
}
