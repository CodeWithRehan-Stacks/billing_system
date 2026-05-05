<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message.
     * 
     * Note: This is a placeholder for actual integration (e.g. Twilio, UltraMsg, etc.)
     */
    public function sendMessage(string $number, string $message): bool
    {
        Log::info("WhatsApp message sent to {$number}: {$message}");

        // Example for a generic API
        /*
        $response = Http::post('https://api.whatsapp-gateway.com/send', [
            'token' => config('services.whatsapp.token'),
            'to' => $number,
            'body' => $message,
        ]);

        return $response->successful();
        */

        return true; 
    }

    /**
     * Send invoice notification to parents.
     */
    public function sendInvoiceNotification($invoice): void
    {
        $student = $invoice->student;
        $message = "Dear Parent,\n\n" .
                   "Your child’s fee invoice for {$invoice->month} {$invoice->year} is generated.\n\n" .
                   "Student: {$student->name}\n" .
                   "Amount: {$invoice->total_amount} PKR\n" .
                   "Due Date: {$invoice->due_date}\n\n" .
                   "Please pay before due date to avoid late fee.\n\n" .
                   "Thank you.\n" .
                   "{$invoice->school->name}";

        // Send to Father (Primary)
        if ($student->father_whatsapp) {
            $this->sendMessage($student->father_whatsapp, $message);
        }

        // Send to Mother (Backup)
        if ($student->mother_whatsapp) {
            $this->sendMessage($student->mother_whatsapp, $message);
        }
    }
}
