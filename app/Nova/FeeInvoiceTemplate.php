<?php

namespace App\Nova;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class FeeInvoiceTemplate extends Resource
{
    public static $model = \App\Models\FeeInvoiceTemplate::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'school_name',
    ];

    public static function label(): string
    {
        return 'Invoice Templates';
    }

    public static function singularLabel(): string
    {
        return 'Invoice Template';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Template Name', 'name')
                ->sortable()
                ->rules('required', 'string', 'max:255'),

            Text::make('School Name', 'school_name')
                ->sortable()
                ->rules('required', 'string', 'max:255'),

            Image::make('Logo', 'logo')
                ->disk('public')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Header Text', 'header_text')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Footer Text', 'footer_text')
                ->nullable()
                ->hideFromIndex(),

            Text::make('Primary Color', 'primary_color')
                ->nullable()
                ->help('Hex color code, e.g. #1a73e8')
                ->hideFromIndex(),

            Select::make('Font Family', 'font_family')
                ->options([
                    'sans-serif' => 'Sans-Serif',
                    'serif'      => 'Serif',
                    'monospace'  => 'Monospace',
                ])
                ->nullable()
                ->displayUsingLabels()
                ->hideFromIndex(),

            Boolean::make('Show Logo', 'show_logo')
                ->default(true),

            Boolean::make('Show Signature', 'show_signature')
                ->default(true),

            Boolean::make('Show QR Code', 'show_qr_code')
                ->default(false),

            Textarea::make('Terms & Conditions', 'terms_conditions')
                ->nullable()
                ->hideFromIndex(),

            Boolean::make('Is Default', 'is_default')
                ->sortable()
                ->help('Only one template can be set as default.'),
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
