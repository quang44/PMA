<?php

    namespace App\Http\Controllers\Api\V2;

    use App\Models\Gift;
    use App\Models\GiftRequest;
    use App\Models\Wallet;
    use Carbon\Carbon;
    use Illuminate\Http\Request;

    class GiftController extends Controller
    {
        function index(Request $request)
        {
            $gift = Gift::query()->orderBy('updated_at', 'DESC');
            if ($request->search) {
                $gift = $gift->where('name', $request->search);
            }
            $gift = $gift->paginate($request->limit ?? 10);
            $gift=  $gift->makeHidden(['created_at','updated_at']);
            foreach ($gift as $item){
                $item->image=uploaded_asset($item->image);
            }
            return $this->sendSuccess($gift);
        }

        function show($id)
        {
            $gift = Gift::findOrFail($id);
            $gift->image=  uploaded_asset($gift->image);
            return $this->sendSuccess($gift);
        }

        function request(Request $request)
        {
            $user = auth()->user();
            $gift = Gift::findOrFail($request->gift);
            $wallet = Wallet::where('user_id', $user->id)->first();
            if(!$wallet){
                $wallet= addWallet($user->id);
            }

            $point =  available_balances($user->id);
            if ($point < $gift->point) {
                return $this->sendError('Điểm của bạn chưa đủ để đổi phần quà này');
            }


          $giftRequest=  GiftRequest::create([
                'user_id' => $user->id,
                'gift_id' => $gift->id,
                'created_time'=>strtotime(now()),
                'address' => $request->address,
            ]);
            return $this->createSuccess($giftRequest ,'Bạn đã đổi quà thành công');
        }


        function CancelRequest(Request $request,$id)
        {
            $user = auth()->user();
            $gift = GiftRequest::where('user_id',$user->id)->findOrFail($id);
            $gift->reason='khách hàng tự hủy';
            $gift->active_time=strtotime(now());
            $gift->status=2;
            $gift->save();
            return  response([
                'result'=>true,
                'message'=>'Bạn đã hủy thành công'
            ]);
        }

        function showRequest(Request $request)
        {
            $user=   auth()->user();
            $gifts = GiftRequest::query()->with('gift')->where('user_id',$user->id);
            $gifts = $gifts->paginate($request->limit ?? 10);

            $data=[];
            foreach ($gifts as $item){
                $data[]= [
                    'id'=>$item->id,
                    'name'=>$item->gift->name,
                    'image'=>uploaded_asset($item->gift->image),
                    'address'=>$item->address,
                    'description'=>$item->gift->description,
                    'status'=>$item->status,
                    'point'=>$item->gift->point
                ];
            }

            return $this->sendSuccess($data);
        }

   function  giftDetail($id){
       $gifts = GiftRequest::query()->with('user.addresses.province') ->findOrFail($id);
       $gifts->created_time=convertTime($gifts->created_time);
       $gifts->active_time=convertTime($gifts->active_time);
       return $this->sendSuccess($gifts);
   }

    }
