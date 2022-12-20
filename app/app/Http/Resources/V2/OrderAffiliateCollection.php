<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderAffiliateCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {

                return [
                    'id'      =>(int) $data->id,
                    'user_id' =>(int) $data->user_id,
                    'user_name' => $data->user->name,
                    'kol_id' => $data->kol_id,
                    'kol_value' => $data->kol_value,
                    'employee_id' => $data->employee_id,
                    'employee_value' => $data->employee_value,
                    'created_time' => $data->created_time
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
