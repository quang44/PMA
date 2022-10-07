<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class QrCodeInformarionCollection extends ResourceCollection
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
                return [
                    'id'=>$data->id,
                    'user_id'=>$data->user_id,
                    'name'=>$data->name,
                    'email'=>$data->email,
                    'phone'=>$data->phone,
                    'status'=>$data->status,
                    'barcode'=>$data->barcode,
                    'created_at' => $data->created_at->format('Y-m-d H:i:s'),
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
