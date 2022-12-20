<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AddressSearchCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {

                return [
                    'id'      =>(int) $data->id,
                    'name' => $data->name,
                    'province_id' => (int) $data->province_id,
                    'district_id' => (int) $data->district_id,
                    'ward_id' => (int) $data->ward_id,
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
