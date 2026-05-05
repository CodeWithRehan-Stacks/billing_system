<?php

namespace App\Nova;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class Receipt extends Resource
{
    public static $model = \App\Models\Receipt::class;

    public static $title = 'receipt_number';

    public static $search = [
        'id', 'receipt_number',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('School', 'school', School::class)
                ->sortable()
                ->canSee(fn ($request) => $request->user()->isSuperAdmin()),

            BelongsTo::make('Invoice', 'invoice', FeeInvoice::class)
                ->sortable(),

            Text::make('Receipt Number', 'receipt_number')
                ->sortable()
                ->readonly(),

            Text::make('File Path', 'file_path')
                ->onlyOnDetail(),

            Text::make('Download', function () {
                if ($this->file_path) {
                    $url = \Illuminate\Support\Facades\Storage::disk('public')->url($this->file_path);
                    return '<a href="' . $url . '" target="_blank" class="no-underline dim text-primary font-bold">Download PDF</a>';
                }
                return 'No file';
            })->asHtml()->exceptOnForms(),

            DateTime::make('Generated At', 'generated_at')
                ->sortable()
                ->readonly(),
        ];
    }

    public static function indexQuery(NovaRequest $request, Builder $query): Builder
    {
        if ($request->user()->isSuperAdmin()) {
            return $query;
        }

        return $query->where('school_id', $request->user()->school_id);
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }
}
