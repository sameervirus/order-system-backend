<?php

namespace App\Http\Resources\api\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->product_id,
            'code' => $this->product->code,
            'client_code' => $this->product->client_code,
            'name' => $this->product->name,
            'arabic_name' => $this->product->name_ar,
            'qty' => $this->qty,
            'qty_approved' => $this->qty_approved,
            'qty_production' => $this->qty_production,
            'qty_delivered' => $this->qty_delivered,
            'comment' => $this->comment
        ];
    }
}
