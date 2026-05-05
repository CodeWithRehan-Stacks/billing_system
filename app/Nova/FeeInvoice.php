<?php

namespace App\Nova;

use App\Nova\Actions\MarkAsPaid;
use App\Nova\Actions\MarkAsOverdue;
use App\Nova\Filters\InvoiceMonthFilter;
use App\Nova\Filters\InvoiceStatusFilter;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class FeeInvoice extends Resource
{
    public static $model = \App\Models\FeeInvoice::class;

    public static $title = 'invoice_number';

    public static $search = [
        'id', 'invoice_number', 'month', 'status',
    ];

    public static function label(): string
    {
        return 'Fee Invoices';
    }

    public static function singularLabel(): string
    {
        return 'Fee Invoice';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('School', 'school', School::class)
                ->sortable()
                ->rules('required'),

            Text::make('Invoice Number', 'invoice_number')
                ->sortable()
                ->readonly()
                ->rules('nullable'),

            BelongsTo::make('Student', 'student', Student::class)
                ->searchable()
                ->sortable()
                ->rules('required'),

            Text::make('Month')
                ->sortable()
                ->rules('required', 'string', 'max:50')
                ->help('e.g. January'),

            Text::make('Year')
                ->sortable()
                ->rules('required', 'string', 'max:4')
                ->help('e.g. 2026'),

            Date::make('Issue Date', 'issue_date')
                ->sortable()
                ->rules('required', 'date'),

            Date::make('Due Date', 'due_date')
                ->sortable()
                ->rules('required', 'date'),

            Currency::make('Base Amount', 'base_amount')
                ->sortable()
                ->currency('PKR')
                ->rules('required', 'numeric', 'min:0'),

            Currency::make('Late Fee', 'late_fee')
                ->currency('PKR')
                ->nullable(),

            Currency::make('Total Amount', 'total_amount')
                ->sortable()
                ->currency('PKR')
                ->rules('required', 'numeric', 'min:0'),

            Currency::make('Paid Amount', 'paid_amount')
                ->sortable()
                ->currency('PKR')
                ->default(0)
                ->readonly(),

            Currency::make('Remaining Amount', 'remaining_amount')
                ->currency('PKR')
                ->onlyOnDetail()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Boolean::make('Late Fee Applied', 'late_fee_applied')
                ->readonly()
                ->hideFromIndex(),

            DateTime::make('Late Fee Applied At', 'late_fee_applied_at')
                ->readonly()
                ->hideFromIndex(),

            Select::make('Status')
                ->options([
                    'pending'  => 'Pending',
                    'sent'     => 'Sent',
                    'partial'  => 'Partial',
                    'paid'     => 'Paid',
                    'overdue'  => 'Overdue',
                ])
                ->displayUsingLabels()
                ->sortable()
                ->rules('required'),

            Badge::make('Status')->map([
                'pending' => 'warning',
                'sent'    => 'info',
                'partial' => 'info',
                'paid'    => 'success',
                'overdue' => 'danger',
            ])->onlyOnIndex(),

            Textarea::make('Remarks')
                ->nullable()
                ->hideFromIndex(),

            HasMany::make('Payments', 'payments', FeePayment::class),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new InvoiceStatusFilter,
            new InvoiceMonthFilter,
        ];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            new MarkAsPaid,
            new MarkAsOverdue,
            new \App\Nova\Actions\ResendWhatsAppNotification,
        ];
    }
}
