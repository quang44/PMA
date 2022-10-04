<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AddressCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {

                return [
                    'id'      =>(int) $data->id,
                    'user_id' =>(int) $data->user_id,
                    'name' => $data->name,
                    'address' => $data->address,
                    'province_id' => (int) $data->province_id,
                    'province_name' => $data->province->name ?? '',
                    'district_id' => (int) $data->district_id,
                    'district_name' =>  $data->district->name ?? '',
                    'ward_id' => (int) $data->ward_id,
                    'ward_name' => $data->ward->name ?? '',
                    'phone' => $data->phone,
                    'set_default' =>(int) $data->set_default,
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'result' => true,
            'message' => null,
        ];
    }
}
