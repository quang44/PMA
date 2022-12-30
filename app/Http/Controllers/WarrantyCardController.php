<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarrantyCardRequest;
use App\Models\Brand;
use App\Models\Color;
use App\Models\CommonConfig;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Upload;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WarrantyCard;
use App\Models\WarrantyCardDetail;
use App\Models\WarrantyCode;
use App\Utility\CustomerBillUtility;
use Illuminate\Http\Request;

class WarrantyCardController extends Controller
{
    //
    function  index(Request $request){
        $search=null;
        $status=null;
         $warranty_cards=WarrantyCard::orderBy('updated_at','desc');
        if($request->search){
            $search=$request->search;
            $warranty_cards =$warranty_cards->where('user_name','like','%'.$search.'%')
                ->orWhere('phone','like','%'.$search.'%')
                ->orWhere('address','like','%'.$search.'%');
        }

        if((isset($request->sort_status) ? $request->sort_status : -1) >= 0){
            $status=$request->sort_status;
            $warranty_cards =$warranty_cards->where('status',$status);
        }
        $warranty_cards=$warranty_cards->with('user','cardDetail.product','active_user_id','district','province','ward')->paginate(15);
        return view('backend.customer.warranty_cards.index',compact('warranty_cards','search','status'));

    }

    public function ban($id,Request $request)
    {

            $WarrantyCard = WarrantyCard::with('brand')->findOrFail(decrypt($id));
            $commonConfig=CommonConfig::first();
            $user=User::find($WarrantyCard->user_id);
            $content="";
            $wallet= Wallet::where('user_id',$WarrantyCard->user_id)->first();
            if(!$wallet){
                $wallet=Wallet::create([
                    'user_id'=>$user->id,
                ]);
            }
            $amount=config_base64_decode($wallet->amount);
         $WarrantyCard->accept_by=auth()->id();
         $WarrantyCard->active_time=strtotime(now());
        if ($request->status==1) {
            $WarrantyCard->status = 1;
//            $amount=config_base64_decode($wallet->amount);
            $wallet->amount=config_base64_encode($amount+$commonConfig->for_activator);
            $wallet->updated_at=date('Y-m-d H:i:s');
            $wallet->save();

            $user->balance=$user->balance+(int)$commonConfig->for_activator;
            $user->save();
            $content="Bạn đã được +$commonConfig->for_activator điểm do kích hoạt thẻ bảo hành thành công của khách hàng $WarrantyCard->user_name  ";

            log_history(['type'=>CustomerBillUtility::TYPE_LOG_ADDITION,
                'point'=>(int)$commonConfig->for_activator,
                'amount'=>(int)$commonConfig->for_activator*$commonConfig->exchange,
                'object'=>0,
                'amount_first'=>(int)$amount,
                'amount_later'=>(int)config_base64_decode($wallet->user_id),
                'user_id'=>$user->id,
                'accept_by'=>auth()->id(),
                'content'=>$content
            ]);
            flash(translate('Thẻ đã được kích hoạt thành công'))->success();
        } else {
            $WarrantyCard->status = 2;
            $content="Yêu cầu bảo hành thiết bị của bạn đã đã bị hủy ";
            $WarrantyCard->reason=$request->reason;
            flash(translate('Thẻ đã được hủy thành công'))->warning();
        }
        $WarrantyCard->save();
//        update_customer_package($user->id);
        sendFireBase($user, "Kích hoạt thẻ bảo hành !", $content, 'warranty', $amount, config_base64_decode($wallet->amount), auth()->id(), $WarrantyCard);
        return back();
    }

    function create(){
        $customers= User::where('banned',0)->orderBy('created_at','desc')->get();
        $products=Product::select('*')->orderBy('created_at','DESC')->get();
        return view('backend.customer.warranty_cards.create',compact('products','customers'));
    }

    function  store(WarrantyCardRequest $request ){
        $warranty_code=WarrantyCode::where('code',$request->warranty_code)->first();
        // user_id, user_name,phone,address,video_url,warranty_code

        $Warranty= new WarrantyCard;
        $Warranty->fill($request->all());
        $Warranty->create_time=now();
        $Warranty->save();
        $WarrantyDetail= new WarrantyCardDetail;
        foreach ($request->product as $key=>$data){
            $WarrantyDetail->create([
                'warranty_card_id'=>$Warranty->id,
                'product_id'=>$data,
                'qty'=>$request->qty[$key],
                'image'=>$request->image[$key],
                'color'=>Color::findOrFail($request->color[$key])->code,
            ]);
        }
        $warranty_code->status=1;
        $warranty_code->use_at=date('Y-m-d H:i:s');
        $warranty_code->save();

        flash(translate('Card has been add new successfully'))->success();
        return redirect()->route('warranty_card.index');
    }

    function  show($id){
        $warranty_card=WarrantyCard::with('cardDetail','active_user_id','province','district','ward')->findOrFail(decrypt($id));
        return view('backend.customer.warranty_cards.show',compact('warranty_card'));
    }


    function  edit(Request $request, $id){
        $warranty=WarrantyCard::findOrFail(decrypt($id));
        $products=Product::select('*')->orderBy('created_at','desc')->get();
        $warrantyDetail=WarrantyCardDetail::with('product')->where('warranty_card_id',$warranty->id)->get();

        return view('backend.customer.warranty_cards.edit',compact('warranty','products','warrantyDetail'));
    }


    function  update(WarrantyCardRequest $request, $id){
        $Warranty=WarrantyCard::findOrFail(decrypt($id));
        $warranty_code=WarrantyCode::where('code',$request->warranty_code)->first();
        $Warranty->fill($request->only(['user_name','address','phone','warranty_code','video_url','note']));
        $Warranty->save();
        $warranty_code->status=1;
        $warranty_code->save();
        if($request->arr_card!=null){
            WarrantyCardDetail::whereIn('id',explode(',',$request->arr_card))->delete();
        }
        foreach ($request->product as $key=>$data){

            $warrantyDetail=WarrantyCardDetail::where('id',$request->card_id[$key])->first();
            if($warrantyDetail){
                $warrantyDetail->update([
                    'product_id'=>$data,
                    'qty'=>$request->qty[$key],
                    'image'=>$request->image[$key],
                    'color'=>Color::findOrFail($request->color[$key])->code,
                ]);
            }else{
                WarrantyCardDetail::create([
                    'warranty_card_id'=>$Warranty->id,
                    'product_id'=>$data,
                    'qty'=>$request->qty[$key],
                    'image'=>$request->image[$key],
                    'color'=>Color::findOrFail($request->color[$key])->code,
                ]);
            }
        }

        flash(translate('Card has been update successfully'))->success();
        return redirect()->route('warranty_card.index');
    }



    public function destroy($id)
    {
        $warrantyCard = WarrantyCard::findOrFail(decrypt($id));
        WarrantyCardDetail::where('warranty_card_id',$warrantyCard->id)->delete();
        $warrantyCard->delete();
        flash(translate('Card has been deleted successfully'))->success();
        return back();
    }
    public function cancel($id)
    {
        $warrantyCard = WarrantyCard::findOrFail(decrypt($id));
        $warrantyCard->status=2;
        flash(translate('Card has been cancel successfully'))->success();
        return back();
    }


       public function card_combination(){
         $products=Product::select('*')->orderBy('created_at','DESC')->get();
        return view('backend.customer.warranty_cards.combinations',compact('products'));
    }

    public function card_combination_edit(Request $request){
        $key=$request->post('key');
        $products=Product::select('*')->orderBy('created_at','DESC')->get();
        return view('backend.customer.warranty_cards.combinations_edit',compact('key','products'));
    }

}
