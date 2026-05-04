<?php

namespace App\Nova\Metrics;

use App\Models\Student;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class ActiveStudents extends Value
{
    public function name(): string
    {
        return 'Active Students';
    }

    public function calculate(NovaRequest $request): \Laravel\Nova\Metrics\ValueResult
    {
        return $this->count($request, Student::where('status', 'active'));
    }

    public function ranges(): array
    {
        return [];
    }

    public function cacheFor()
    {
        return now()->addMinutes(15);
    }

    public $uriKey = 'active-students';
}
