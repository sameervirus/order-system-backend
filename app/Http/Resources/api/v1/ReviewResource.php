<?php

namespace App\Http\Resources\api\v1;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\api\v1\OrderResouce;

class ReviewResource extends JsonResource
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
            'id' => $this->id,
            'client_code' => $this->client_code,
            'name' => $this->name,
            'arabic_name' => $this->name_ar,
            'orders' => OrderResouce::collection($this->productOrders)
        ];
    }
}
