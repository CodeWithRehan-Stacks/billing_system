<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class FeeInvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'invoiceNumber' => $this->invoice_number,
            'studentId' => $this->student_id,
            'student' => new StudentResource($this->whenLoaded('student')),
            'billingPeriod' => [
                'month' => $this->month,
                'year' => $this->year,
            ],
            'dates' => [
                'issued' => $this->issue_date,
                'due' => $this->due_date,
            ],
            'amounts' => [
                'total' => (float) $this->total_amount,
                'paid' => (float) $this->paid_amount,
                'lateFee' => (float) $this->late_fee,
                'remaining' => (float) $this->remaining_amount,
            ],
            'status' => $this->status,
            'lateFeeApplied' => (bool) $this->late_fee_applied,
            'createdAt' => $this->created_at->toISOString(),
            'updatedAt' => $this->updated_at->toISOString(),
        ];
    }
}
