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
                        'content' => $data->data,
                        'amount_first' => $data->amount_first,
                        'amount_later' => $data->amount_later,
                        'title'=>CustomerBillUtility::$arrayTypeNotification[$data->type],
                        'date' => convertTime( $data->updated_at)
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