<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\InvoiceGenerationService;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly fee invoices for all active students and apply late fees';

    /**
     * Execute the console command.
     */
    public function handle(InvoiceGenerationService $invoiceService)
    {
        $this->info('Applying late fees to overdue invoices...');
        $invoiceService->applyLateFees();
        
        $this->info('Generating monthly invoices...');
        $invoiceService->generateMonthlyInvoices();
        
        $this->info('Invoices generation process complete!');
    }
}
