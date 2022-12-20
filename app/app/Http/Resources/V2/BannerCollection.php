<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BannerCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {

                return [
                    'name' => $data->name,
                    'image' => uploaded_asset($data->image),
                    'type' => $data->type,
                    'link' => $data->link
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'result' => true,
        ];
    }
}
