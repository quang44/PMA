<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WarrantyCardCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->map(function ($data){
                        return [
                            'id'=>(int) $data->id,
                            'name' => $data->name,
                            'slug'=>$data->slug,
                            'seri'=>$data->seri,
                            'qr_code_image'=>uploaded_asset($data->qr_code_image),
                            'seri_image'=>uploaded_asset($data->seri_image),

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
