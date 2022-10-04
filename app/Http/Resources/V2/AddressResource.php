<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'data' => [
                'id'      =>(int) $this->id,
                'user_id' =>(int) $this->user_id,
                'name' => $this->name ?? '',
                'address' => $this->address,
                'province_id' => (int) $this->province_id,
                'province_name' => $this->province->name ?? '',
                'district_id' => (int) $this->district_id,
                'district_name' =>  $this->district->name ?? '',
                'ward_id' => (int) $this->ward_id,
                'ward_name' => $this->ward->name ?? '',
                'phone' => $this->phone,
                'set_default' =>(int) $this->set_default,
            ]
        ];
    }

    public function with($request)
    {
        return [
            'result' => true,
            'message' => null
        ];
    }
}
