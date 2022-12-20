<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WarrantyCard extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        return [
            'data' => $this->collection->map(function ($data) {


                return
                    [
                        'id' => $data->id,
                        'user_name' => $data->user_name,
                        'phone'=>$data->phone,
                        'address' => $data->address,
                        'province'=>$data->province->name,
                        'district'=>$data->district->name,
                        'ward'=>$data->ward->name,
                        'status' => $data->status,
                        'reason' => $data->reason,
                        'note' => $data->note,
                        'active_time' => convertTime($data->active_time),
                        'created_at' => convertTime($data->create_time),
                    ];
            })
        ];
    }


}
