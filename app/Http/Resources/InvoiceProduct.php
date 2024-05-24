<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class InvoiceProduct extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'sale_price' => $this->price,
            'quantity' => $this->quantity,
            'invoice_id' => $this->invoice_id,
            "created_at" =>  Carbon::create($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
