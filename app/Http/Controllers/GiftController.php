<?php

    namespace App\Http\Controllers;

    use App\Models\CommonConfig;
    use App\Models\Gift;
    use App\Models\GiftRequest;
    use App\Models\User;
    use App\Models\Wallet;
    use App\Utility\CustomerBillUtility;
    use Illuminate\Http\Request;
    use function Sodium\add;

    class GiftController extends Controller
    {

        public function index(Request $request)
        {
            $gifts = Gift::query()->orderBy('updated_at', 'desc');
            $sort_status = null;
            $search = null;
            if ((isset($request->sort_status) ? $request->sort_status : -1) >= 0) {
                $sort_status = $request->sort_status;
                $gifts = $gifts->where('status', $sort_status);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $gifts = $gifts->where('name', 'like', '%' . $search . '%');
            }
            $gifts = $gifts->paginate(15);

            return view('backend.marketing.gift.index', compact('gifts', 'sort_status', 'search'));

        }


        public function create()
        {
            return view('backend.marketing.gift.create');
        }


        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required',
                'point' => 'required'
            ]);
            $gift = new Gift();
            $gift->fill($request->all());
            $gift->save();
            flash(translate('Gift has been inserted  successfully'))->success();
            return redirect()->route('gift.index');
        }

        public function show( $id)
        {
            $gift=GiftRequest::with('user','gift')->findOrFail(decrypt($id));
            return view('backend.marketing.gift.show',compact('gift'));
        }


        public function edit($id)
        {
            $gift = Gift::findOrFail(decrypt($id));
            return view('backend.marketing.gift.update ', compact('gift'));
        }

        public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required',
                'point' => 'required|integer'
            ]);
            $gift = Gift::findOrFail($id);
            $gift->fill($request->all());
            $gift->updated_at=date('Y-m-d H:i:s');
            $gift->save();
            flash(translate('Gift has been updated successfully'))->success();
            return redirect()->route('gift.index');
        }


        function updateStatus(Request $request)
        {
            $Gift = Gift::find($request->id);
            if ($Gift != null) {
                $Gift->status = $request->status;
                $Gift->save();
                return response([
                    'result' => 1,
                ]);
            } else {
                return response([
                    'result' => 0,
                ]);
            }
        }


        public function destroy($id)
        {
            $gift = Gift::findOrFail($id);
            $gift->delete();
            flash(translate('Gift has been deleted successfully'));
            return redirect()->route('gift.index');

            //
        }

        public function bulk_delete(Request $request)
        {
            foreach ($request->id as $item) {
                Gift::find($item)->delete();
            }
            flash(translate('Gift has been deleted successfully'));
            return 1;

            //
        }


        function giftRequest(Request $request)
        {
            $giftRequest = GiftRequest::query()->with('user', 'gift','accept')->orderBy('updated_at', 'DESC');
            $sort_search = null;
             $search=null;
            if ((isset($request->sort_status) ? $request->sort_status : -1) >= 0) {
                $sort_search = $request->sort_status;
                $giftRequest = $giftRequest->where('status', $sort_search);
            }
            if (isset($request->search) ) {
                $search = $request->search;
                $user=User::query()->where('name','like',"%$search%")
                    ->orWhere('phone','like',"%$search%")
                    ->pluck('id');
//                dd($user);
                $giftRequest= $giftRequest->whereIn('user_id', $user);
//                    return $query->whereIn('user_id', $user);
//                });
//                $giftRequest = $giftRequest->where('status', $search);
            }
            $giftRequest = $giftRequest->paginate(15);


            return view('backend.marketing.gift.request', compact('giftRequest', 'sort_search','search'));
        }



         function giftBan( Request $request,$id)
        {
            $giftRequest = GiftRequest::with('gift')->findOrFail(decrypt($id));
//            dd($giftRequest);
            $commonConfig = CommonConfig::first();
            $user = User::find($giftRequest->user_id);
//            $content = "";
            $wallet = Wallet::where('user_id', $giftRequest->user_id)->first();
            if(!$wallet){
                $wallet=addWallet($giftRequest->user_id);
            }
             $amount =config_base64_decode($wallet->amount);
             $giftRequest->accept_by=auth()->id();
             $giftRequest->active_time = strtotime(now());

             if ($request->status == 1) {
                $giftRequest->status = 1;
                $point = (int)$amount - (int)$giftRequest->gift->point;
                $wallet->amount = $point > 0 ? config_base64_encode($point) : config_base64_encode(0);
                $wallet->save();
//                $user->balance = $user->balance + (int)$commonConfig->for_activator;
                $user->save();
                $content = " Bạn đã bị -".$giftRequest->gift->point." điểm cho yêu cầu đổi quà ".$giftRequest->gift->name." thành công, chúng tôi sẽ hỗ trợ gửi quà sớm nhất cho bạn";
                log_history(['type' => CustomerBillUtility::TYPE_LOG_WITHDRAW,
                    'point' => -$giftRequest->gift->point,
                    'amount' => -$giftRequest->gift->point * $commonConfig->exchange,
                    'object' => 0,
                    'amount_first' => (int)$amount,
                    'amount_later' => config_base64_decode($wallet->amount),
                    'user_id' => $user->id,
                    'accept_by' => auth()->id(),
                    'content' => $content
                ]);

                flash('Quà đã được phê duyệt thành công')->success();
//                if (!$wallet) {
//                    update_customer_package($wallet->user_id);
//                }
            } else {
                $giftRequest->status = 2;
                $content = "Yêu cầu đổi quà " . $giftRequest->gift->name . " của bạn đã bị hủy , lý do : $request->reason";
                $giftRequest->reason = $request->reason;
                flash(translate('Quà đã được hủy thành công'))->warning();
            }

            $giftRequest->save();
             sendFireBase($user, "Thông báo đổi quà !", $content, 'gift', $amount, config_base64_decode($wallet->amount), auth()->id(), $giftRequest);
            return back();
        }

    }
