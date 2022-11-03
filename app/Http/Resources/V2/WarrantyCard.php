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
                        'brand' =>$data->brand!=null?$data->brand->name:'Hãng sản xuất không tồn tại',
                        'user_name' => $data->user_name,
                        'address' => $data->address,
                        'point' =>$data->point,
                        'image' => image_asset_by_object($data->id),
                        'status'=>$data->status,
                        'reason'=>$data->reason,
                        'active_time'=> date('d/m/Y H:i:s', strtotime($data->active_time)),
                        'created_at' => date('d/m/Y H:i:s', strtotime($data->created_at)),
                    ];
            })
        ];
    }
}
