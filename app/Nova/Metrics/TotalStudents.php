<?php

namespace App\Nova\Metrics;

use App\Models\Student;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class TotalStudents extends Value
{
    public function name(): string
    {
        return 'Total Students';
    }

    public function calculate(NovaRequest $request): \Laravel\Nova\Metrics\ValueResult
    {
        return $this->count($request, Student::class);
    }

    public function ranges(): array
    {
        return [];
    }

    public function cacheFor()
    {
        return now()->addMinutes(15);
    }

    public $uriKey = 'total-students';
}
