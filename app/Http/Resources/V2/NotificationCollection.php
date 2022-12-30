<?php

    namespace App\Http\Resources\V2;

    use App\Utility\CustomerBillUtility;
    use Carbon\Carbon;
    use Illuminate\Http\Resources\Json\ResourceCollection;

    class NotificationCollection extends ResourceCollection
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
                    return [
                        'id' => $data->id,
                        'type'=>$data->type,
                        'user_id' => $data->user_id,
                        'title'=>CustomerBillUtility::$arrayTypeNotification[$data->type],
                        'content' => $data->data,
                        'amount_first' => $data->amount_first,
                        'amount_later' => $data->amount_later,
                        'date' => convertTime($data->created_at),
                        'read_at'=>$data->read_at,
                        'item_id'=>$data->item_id
                    ];

                })
            ];
        }


        public function with($request)
        {
            return [
                'result' => true,
                'message' => 'successfully',
            ];
        }
    }
