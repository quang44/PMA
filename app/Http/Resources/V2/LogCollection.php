<?php

namespace App\Http\Resources\V2;

use App\Utility\CustomerBillUtility;
use App\Utility\WarrantyCardUtility;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LogCollection extends ResourceCollection
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
                    'id' => $data->id,
                    'point'=>$data->point,
                    'title'=>CustomerBillUtility::$arrayTypeLog[$data->type],
                    'user_id' => intval($data->user_id),
                    'content' => $data->content,
                    'time'=>date('d/m/Y H:i:s',strtotime($data->created_at)),
                ];
            })
        ];

    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
