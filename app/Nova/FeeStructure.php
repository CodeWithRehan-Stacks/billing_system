<?php

namespace App\Nova;

use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class FeeStructure extends Resource
{
    public static $model = \App\Models\FeeStructure::class;

    public static $title = 'fee_type';

    public static $search = [
        'id', 'class', 'fee_type',
    ];

    public static function label(): string
    {
        return 'Fee Structures';
    }

    public static function singularLabel(): string
    {
        return 'Fee Structure';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Select::make('Class')
                ->options(function () {
                    $classes = [];
                    foreach (range(1, 12) as $cls) {
                        $classes["Class $cls"] = "Class $cls";
                    }
                    $classes['KG']     = 'KG';
                    $classes['Pre-KG'] = 'Pre-KG';
                    return $classes;
                })
                ->rules('required')
                ->displayUsingLabels()
                ->sortable(),

            Select::make('Fee Type', 'fee_type')
                ->options([
                    'Tuition Fee'   => 'Tuition Fee',
                    'Transport Fee' => 'Transport Fee',
                    'Library Fee'   => 'Library Fee',
                    'Lab Fee'       => 'Lab Fee',
                    'Sports Fee'    => 'Sports Fee',
                    'Other'         => 'Other',
                ])
                ->rules('required')
                ->displayUsingLabels()
                ->sortable(),

            Currency::make('Amount')
                ->currency('PKR')
                ->sortable()
                ->rules('required', 'numeric', 'min:0'),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
