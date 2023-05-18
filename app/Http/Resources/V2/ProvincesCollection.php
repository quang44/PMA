<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProvincesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'id'      =>(int) $data->id,
                    'name' => $data->name,
                    'type' => $data->type,
                    'latlng'=>$data->latlng
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'result' => true
        ];
    }
}
