<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ApiForamtProduct extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" =>  $this->id,
            "category_id" =>  $this->category_id,
            "category_name" =>  $this->category->name,
            "name" =>  $this->name,
            "buy_price" =>  $this->buy_price,
            "min_sale" =>  $this->min_sale,
            "normal_sale" =>  $this->normal_sale,
            "created_at" =>    Carbon::create($this->created_at)->format('Y-m-d H:i:s'),
            "updated_at" =>  Carbon::create($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
