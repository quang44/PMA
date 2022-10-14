<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WarrantyCard extends ResourceCollection
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
            'data'=>$this->collection->map(function ($data){
                return
                    [
                        'id'=>$data->id,
                        'user_name'=>$data->user_name,
                        'address'=>$data->address,
                        'point'=>$data->point,
                        'qr_code_img'=>uploaded_asset($data->qr_code_image),
                        'seri_image'=>uploaded_asset($data->seri_image),

                    ];
            })
        ];
    }
}
