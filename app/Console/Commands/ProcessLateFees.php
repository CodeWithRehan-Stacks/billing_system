<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\InvoiceService;

class ProcessLateFees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:apply-late-fees';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Apply late fees to overdue invoices';

    /**
     * Execute the console command.
     */
    public function handle(InvoiceService $invoiceService)
    {
        $this->info('Applying late fees...');
        $count = $invoiceService->applyLateFees();
        $this->info("Late fees applied to {$count} invoices.");
    }
}
