<?php

namespace App\Http\Resources\InvoiceDetails;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id'=>$this->product_id,
            'qty'=>$this->qty,
            'sale_price' => $this->sale_price
        ];
    }
}
