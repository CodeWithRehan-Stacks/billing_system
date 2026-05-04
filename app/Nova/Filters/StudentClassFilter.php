<?php

namespace App\Nova\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class StudentClassFilter extends Filter
{
    public $name = 'Class';

    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('class', $value);
    }

    public function options(NovaRequest $request): array
    {
        $classes = [];
        foreach (range(1, 12) as $cls) {
            $classes["Class $cls"] = "Class $cls";
        }
        $classes['KG']     = 'KG';
        $classes['Pre-KG'] = 'Pre-KG';
        return $classes;
    }
}
