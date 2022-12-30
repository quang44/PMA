<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Log;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function balance()
    {
        $user = User::find(auth()->user()->id);
        $latest = Wallet::where('user_id', auth()->user()->id)->latest()->first();
        return response()->json([
            'balance' => format_price($user->balance),
            'last_recharged' => $latest == null ? "Not Available" : $latest->created_at->diffForHumans(),
        ]);
    }

//  lịch sử
    public function walletRechargeHistory(Request $request)
    {
        $logs = Log::query()
            ->with(['acceptor'])
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('updated_at')
            ->paginate($request->limit ?? 10);

        $logs->getCollection()->transform(function ($value) {
            $value->makeHidden(['created_at', 'updated_at', 'object', 'amount', 'type']);
            $value->time = convertTime($value->updated_at);
            return $value;
        });

//        $wallet=  $wallet->makeHidden(['created_at','updated_at','object','amount','point','type','id']);
        return response([
            'data' => $logs->values(),
            'result' => true
        ], 200);
//        return new WalletCollection($wallet);
    }


//    chi Tiết lịch sử
    function walletRechargeHistoryDetail($id)
    {
        $logs = Log::query()->findOrFail($id);
        $logs->load('card', 'gifts');
        $logs->makeHidden(['gifts', 'card', 'created_at', 'updated_at', 'object', 'amount', 'type','item_id']);

        $data = null;
        if (!is_null($logs->gifts) && $logs->type==1) {
            $data = [
                'username' => $logs->gifts->user->name,
                'name' => $logs->gifts->gift->name,
                'status' => $logs->gifts->status,
                'reason' => $logs->gifts->reason,
                'accept_by' => !$logs->accept ? null : $logs->accept->name,
                'point' => $logs->gifts->gift->point,
                'address' => $logs->gifts->address
            ];
        }
        if (!is_null($logs->card) && $logs->type==2) {
            $product = '';
            foreach ($logs->card->cardDetail as $item) {
                $name = $item->product->name;
                $product .= ucfirst($name) . ',';
            }
            $address = $logs->card->address . ', ' . $logs->card->ward->name . ', ' . $logs->card->district->name . ', ' . $logs->card->province->name;
            $data = [
                'username' => $logs->card->user_name,
                'name' => rtrim($product, ','),
                'status' => $logs->card->status,
                'reason' => $logs->card->reason,
                'accept_by' => !$logs->active_user_id ? null : $logs->active_user_id->name,
                'point' => $logs->card->point,
                'address' => $address
            ];
        }
        $logs->item = $data;
        return  $this->sendSuccess($logs);

    }


    public function processPayment(Request $request)
    {
        $order = new OrderController;
        $user = User::find($request->user_id);

        if ($user->balance >= $request->amount) {

            $response = $order->store($request, true);
            $decoded_response = $response->original;
            if ($decoded_response['result'] == true) { // only decrease user balance with a success
                $user->balance -= $request->amount;
                $user->save();
            }

            return $response;

        } else {
            return response()->json([
                'result' => false,
                'combined_order_id' => 0,
                'message' => translate('Insufficient wallet balance')
            ]);
        }
    }

//    public function offline_recharge(Request $request)
//    {
//        $wallet = new Wallet;
//        $wallet->user_id = auth()->user()->id;
//        $wallet->amount = $request->amount;
//        $wallet->payment_method = $request->payment_option;
//        $wallet->payment_details = $request->trx_id;
//        $wallet->approval = 0;
//        $wallet->offline_payment = 1;
//        $wallet->reciept = $request->photo;
//        $wallet->save();
//       // flash(translate('Offline Recharge has been done. Please wait for response.'))->success();
//        //return redirect()->route('wallet.index');
//        return response()->json([
//            'result' => true,
//            'message' => translate('Offline Recharge has been done. Please wait for response.')
//        ]);
//    }

}
