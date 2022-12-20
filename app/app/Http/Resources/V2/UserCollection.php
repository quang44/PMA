<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'id' => (integer) $data->id,
                    'name' => $data->name,
                    'type' => $data->user_type,
                    'email' => $data->email,
                    'avatar' => $data->avatar,
                    'avatar_original' => uploaded_asset($data->avatar_original),
                    'address' => $data->address,
                    'city' => $data->city,
                    'country' => $data->country,
                    'postal_code' => $data->postal_code,
                    'phone' => $data->phone,
                    'count_shop' => $data->count_shop ?? 0,
                    'count_order' => $data->count_order ?? 0,
                    'created_at' => $data->created_at->format('Y-m-d H:i:s'),
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'result' => true,
            'meta' => [
                'total' => 0
            ]
        ];
    }
}
