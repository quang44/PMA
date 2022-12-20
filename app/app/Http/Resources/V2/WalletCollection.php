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
                return $dataLogs;

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
