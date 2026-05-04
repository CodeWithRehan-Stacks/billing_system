<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class MarkAsOverdue extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Mark as Overdue';

    public function handle(ActionFields $fields, Collection $models): void
    {
        foreach ($models as $invoice) {
            if (in_array($invoice->status, ['pending', 'partial'])) {
                $lateFee = $invoice->late_fee_type === 'percentage'
                    ? $invoice->total_amount * ($invoice->late_fee_value / 100)
                    : ($invoice->late_fee_value ?? 0);

                $invoice->update([
                    'status'           => 'overdue',
                    'late_fee'         => $lateFee,
                    'late_fee_applied' => true,
                    'total_amount'     => $invoice->total_amount + $lateFee,
                ]);
            }
        }
    }

    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
