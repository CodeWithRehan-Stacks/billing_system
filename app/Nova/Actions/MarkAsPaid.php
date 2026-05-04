<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class MarkAsPaid extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Mark as Paid';

    public function handle(ActionFields $fields, Collection $models): void
    {
        foreach ($models as $invoice) {
            $invoice->update([
                'paid_amount' => $invoice->total_amount,
                'status'      => 'paid',
            ]);
        }
    }

    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
