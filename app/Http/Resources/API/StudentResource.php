<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class StudentResource extends JsonResource
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
            'name' => $this->name,
            'fatherName' => $this->father_name,
            'motherName' => $this->mother_name,
            'phone' => $this->phone,
            'studentWhatsapp' => $this->student_whatsapp,
            'fatherWhatsapp' => $this->father_whatsapp,
            'motherWhatsapp' => $this->mother_whatsapp,
            'address' => $this->address,
            'class' => $this->class,
            'section' => $this->section,
            'rollNumber' => $this->roll_number,
            'admissionDate' => $this->admission_date ? Carbon::parse($this->admission_date)->format('Y-m-d') : null,
            'status' => $this->status,
            'monthlyFee' => (float) $this->monthly_fee,
            'createdAt' => $this->created_at->toISOString(),
            'updatedAt' => $this->updated_at->toISOString(),
        ];
    }
}
