<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BrandCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->getTranslation('name'),
                    'slug' => $data->slug,
                    'logo' => uploaded_asset($data->logo),
                    'code'=>$data->code,
                    'status'=>$data->status==0?'hide':'show',
                    'created_at'=>date('d-m-Y H:i:s',strtotime($data->created_at))
//                    'links' => [
//                        'products' => route('api.products.brand', $data->id)
//                    ]
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'result' => true,
            'status' => 200
        ];
    }
}
