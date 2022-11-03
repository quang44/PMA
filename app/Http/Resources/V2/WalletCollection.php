<?php

namespace App\Http\Resources\V2;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WalletCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                $dataLogs=[];
                foreach ($data->logs as $val){
                    if($val->accept_by!=null) {
                     $accept_by=  $val->acceptor->user_type=='admin'?'Admin':'Accountant';
                    }else{
                     $accept_by=null;
                    }
                    $dataLogs[]=[
                        'id'=>$val->id,
                        'point'=>$val->point,
                        'amount_first'=>$val->amount_first,
                        'amount_later'=>$val->amount_later,
                        'accept_by'=> $accept_by ,
                        'content'=>$val->content
                    ];
                }
                return [
                    'amount' => available_balances($data->user_id)  ,
                    'package'=>$data->user->customer_package->name,
//                    'payment_method' => ucwords(str_replace('_', ' ', $data->payment_method)),
//                    'approval_string' => $data->offline_payment ? ($data->approval == 1 ? "Approved" : "Decliend") : "N/A",
//                    'date' => Carbon::createFromTimestamp(strtotime($data->created_at))->format('d-m-Y'),
                    'walletRechargeHistory'=>$dataLogs,
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
