<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BannerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'data' => [
                'name' => $this->name,
                'image' => uploaded_asset($this->image),
                'type' => $this->type,
                'link' => $this->link,
                'content' => $this->content,
            ]
        ];
    }

    public function with($request)
    {
        return [
            'result' => true,
            'message' => null
        ];
    }
}
