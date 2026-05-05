<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\InvoiceService;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate {--school_id= : The ID of the school}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Generate monthly invoices for active students';

    /**
     * Execute the console command.
     */
    public function handle(InvoiceService $invoiceService)
    {
        $this->info('Generating invoices...');
        $results = $invoiceService->generateMonthlyInvoices($this->option('school_id'));
        
        foreach ($results as $schoolId => $data) {
            $this->info("School: {$data['name']} - Generated: {$data['generated']}");
        }
        
        $this->info('Invoice generation completed.');
    }
}
