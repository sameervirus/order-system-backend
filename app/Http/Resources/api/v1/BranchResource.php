<?php

namespace App\Http\Resources\api\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'name' => $this->name,
            'arabic_name' => $this->name_ar,
            'city' => $this->city->name,
            'client' => $this->client->name,
            'arabic_client' => $this->client->name_ar,
            'client_code' => $this->client->code,
            'district' => $this->district->name,
            'arabic_district' => $this->district->name_ar,
            'due_period' => $this->due_period,
            'last_time_request' => $this->close_time,
            'code' => $this->code
        ];
    }
}
