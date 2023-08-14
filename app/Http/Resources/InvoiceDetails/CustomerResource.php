<?php

namespace App\Http\Resources\InvoiceDetails;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile
        ];
    }
}
