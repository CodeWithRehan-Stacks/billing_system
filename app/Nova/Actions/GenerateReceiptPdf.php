<?php

namespace App\Nova\Actions;

use App\Models\FeePayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class GenerateReceiptPdf extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Generate Receipt PDF';

    /**
     * Only allow on a single payment at a time.
     */
    public $sole = true;

    public function handle(ActionFields $fields, Collection $models): \Laravel\Nova\Actions\ActionResponse
    {
        /** @var FeePayment $payment */
        $payment = $models->first();
        $payment->load(['invoice.student', 'invoice.items']);

        $data = [
            'payment' => $payment,
            'student' => $payment->invoice->student,
            'invoice' => $payment->invoice,
        ];

        $filename = 'receipt-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT) . '.pdf';
        $pdf      = Pdf::loadView('receipts.pdf', $data);

        // Store to temporary public location then return download
        $path = storage_path('app/public/receipts/' . $filename);

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $pdf->save($path);

        return Action::download(
            route('receipt.download', ['payment' => $payment->id]),
            $filename
        );
    }

    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
