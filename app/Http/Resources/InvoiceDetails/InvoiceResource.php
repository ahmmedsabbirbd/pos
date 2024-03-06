<?php

namespace App\Http\Resources\InvoiceDetails;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'total'=> $this->total,
            'discount'=> $this->discount,
            'vat'=> $this->vat,
            'payable'=> $this->payable,
        ];
    }
}
