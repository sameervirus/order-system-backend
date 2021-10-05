<?php

namespace App\Http\Resources\api\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResouce extends JsonResource
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
            'id'                => $this->id,
            'code'              => $this->code,
            'date'              => $this->date,
            'due_date'          => $this->due_date,
            'branch'            => $this->branch->name,
            'arabic_branch'     => $this->branch->name_ar,
            'branch_code'       => $this->branch->code,
            'client'            => $this->branch->client->name,
            'arabic_client'     => $this->branch->client->name_ar,
            'client_code'       => $this->branch->client->code,
            'district'          => $this->branch->district->name,
            'arabic_district'   => $this->branch->district->name_ar,
            'city'              => $this->branch->city->name,
            'status'            => $this->status->name,
            'status_id'         => $this->status_id,
            'qty'               => $this->qty(),
            'approved'          => $this->approved(),
            'creater'           => $this->creater->name,
            'pivot_qty'         => optional($this->pivot)->qty,
            'pivot_approve'     => optional($this->pivot)->qty_approved,
            'pivot_production'  => optional($this->pivot)->qty_production,
            'pivot_delivered'   => optional($this->pivot)->qty_delivered,
            'submit'            => optional($this->confirmer)->name,
            'editer'            => optional($this->editer)->name,
            'approval'          => optional($this->approval)->name,
            'production'        => optional($this->production)->name,
            'driver'            => optional($this->driver)->name,
            'recevier'          => optional($this->recevier)->name
        ];
    }
}
