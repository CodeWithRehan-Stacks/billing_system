<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class ErrorLog extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ErrorLog>
     */
    public static $model = \App\Models\ErrorLog::class;

    /**
     * The single value that should be used to represent the resource during Sonnen.
     *
     * @var string
     */
    public static $title = 'message';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'message', 'level', 'file'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Level')
                ->sortable()
                ->displayUsing(function ($level) {
                    $color = match ($level) {
                        'error' => 'text-red-500',
                        'warning' => 'text-yellow-500',
                        default => 'text-blue-500',
                    };
                    return "<span class='{$color} font-bold'>" . strtoupper($level) . "</span>";
                })->asHtml(),

            Text::make('Message')->onlyOnIndex(),
            Text::make('Message')->hideFromIndex(),

            Text::make('File')->sortable(),
            Number::make('Line')->sortable(),

            BelongsTo::make('User')->nullable()->sortable(),

            DateTime::make('Created At')->sortable(),

            Code::make('Stack Trace')
                ->language('php')
                ->onlyOnDetail(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public static function availableForNavigation(Request $request)
    {
        return $request->user()->isSuperAdmin();
    }
}
