<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductMiniCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->getTranslation('name'),
                    'thumbnail_image' => uploaded_asset($data->thumbnail_img),
                    'description'=>strip_tags($data->description),
                    'warranty_duration'=>timeWarranty( (int)$data->warranty_duration),
//                    'has_discount' => home_base_price($data, false) != home_discounted_base_price($data, false) ,
//                    'stroked_price' => home_base_price($data),
//                    'main_price' => home_discounted_base_price($data),
//                    'rating' => (double) $data->rating,
//                    'sales' => (integer) $data->num_of_sale,
//                    'warranty_duration'=>$data->warranty_duration.' Năm',
//                    'links' => [
//                        'details' => route('products.show', $data->id),
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
