<?php

namespace App\Services;

use App\Models\FeeInvoice;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send invoice notification via WhatsApp.
     */
    public function sendInvoiceNotification(FeeInvoice $invoice)
    {
        $student = $invoice->student;
        $school = $invoice->school;

        $message = "Dear Parent,\n\n"
                 . "Your child’s fee invoice for {$invoice->month} {$invoice->year} is generated.\n\n"
                 . "Student: {$student->name}\n"
                 . "Amount: " . number_format($invoice->total_amount, 2) . " PKR\n"
                 . "Due Date: " . \Carbon\Carbon::parse($invoice->due_date)->format('d F Y') . "\n\n"
                 . "Please pay before due date to avoid late fee.\n\n"
                 . "Thank you.\n"
                 . "{$school->name}";

        // Primary: Father
        $this->sendMessage($student->father_whatsapp ?? $student->phone, $message);
        
        // Backup: Mother
        if ($student->mother_whatsapp) {
            $this->sendMessage($student->mother_whatsapp, $message);
        }

        // Optional: Student
        if ($student->student_whatsapp) {
            $this->sendMessage($student->student_whatsapp, $message);
        }

        $invoice->status = 'sent';
        $invoice->save();

        return true;
    }

    /**
     * Mock function for sending a message.
     */
    protected function sendMessage($number, $message)
    {
        if (empty($number)) return false;

        Log::info("WhatsApp Message Sent to {$number}: {$message}");
        
        // In a real scenario, you would call an API here.
        // Example: Http::post('https://api.whatsapp.com/send', [...]);
        
        return true;
    }
}
