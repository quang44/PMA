<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\AffiliatePayment;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WarrantyCard;
use Illuminate\Http\Request;

class StatisticalController extends Controller
{
    public function index(){
        $total_worker = User::where('user_type', 'customer' )->count();
        $history_withdraws = AffiliatePayment::where('status', 2)->get();
        $wallet = Wallet::where('id', '>', 0)->get();
        $total_withdraw = 0;
        $total_not_withdraw = 0;
        $total_active = WarrantyCard::where('status', 1)->count();
        if($history_withdraws != null){
            foreach ($history_withdraws as $key => $history_withdraw){
                $total_withdraw += $history_withdraw->amount;
            }
        }
        if($wallet != null){
            foreach ($wallet as $key => $not_withdraw){
                $total_not_withdraw += config_base64_decode($not_withdraw->amount);
            }
        }

        $data[] = [
            'total_worker'=>$total_worker,
            'total_withdraw'=>$total_withdraw,
            'total_not_withdraw'=>$total_not_withdraw,
            'total_active'=>$total_active
        ];
        return response()->json([
            'result'=>true,
            'data'=> $data

        ]);
    }

}
