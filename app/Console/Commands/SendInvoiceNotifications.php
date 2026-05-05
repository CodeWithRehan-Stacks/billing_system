<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FeeInvoice;
use App\Services\WhatsAppService;

class SendInvoiceNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:send-whatsapp';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Send pending invoice notifications via WhatsApp';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsAppService)
    {
        $this->info('Sending WhatsApp notifications...');
        $invoices = FeeInvoice::withoutGlobalScopes()
            ->where('status', 'pending')
            ->get();

        $count = 0;
        foreach ($invoices as $invoice) {
            try {
                $this->info("Sending to invoice #{$invoice->invoice_number}...");
                $whatsAppService->sendInvoiceNotification($invoice);
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to send invoice #{$invoice->invoice_number}: " . $e->getMessage());
            }
        }
        
        $this->info("Sent {$count} notifications.");
    }
}
