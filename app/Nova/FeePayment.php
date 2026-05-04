<?php

namespace App\Nova;

use App\Nova\Actions\GenerateReceiptPdf;
use App\Nova\Filters\PaymentMethodFilter;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class FeePayment extends Resource
{
    public static $model = \App\Models\FeePayment::class;

    public static $title = 'id';

    public static $search = [
        'id', 'transaction_id',
    ];

    public static function label(): string
    {
        return 'Fee Payments';
    }

    public static function singularLabel(): string
    {
        return 'Fee Payment';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Invoice', 'invoice', FeeInvoice::class)
                ->searchable()
                ->sortable()
                ->rules('required'),

            BelongsTo::make('Student', 'student', Student::class)
                ->searchable()
                ->readonly()
                ->hideWhenCreating(),

            Currency::make('Amount')
                ->currency('PKR')
                ->sortable()
                ->rules('required', 'numeric', 'min:1'),

            Select::make('Payment Method', 'payment_method')
                ->options([
                    'cash'          => 'Cash',
                    'bank_transfer' => 'Bank Transfer',
                    'online'        => 'Online',
                ])
                ->rules('required')
                ->displayUsingLabels()
                ->sortable(),

            Date::make('Payment Date', 'payment_date')
                ->sortable()
                ->nullable()
                ->default(now()),

            Text::make('Transaction ID', 'transaction_id')
                ->nullable()
                ->hideFromIndex(),

            Textarea::make('Notes')
                ->nullable()
                ->hideFromIndex(),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new PaymentMethodFilter,
        ];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            new GenerateReceiptPdf,
        ];
    }
}
