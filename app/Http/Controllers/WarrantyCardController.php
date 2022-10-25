<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarrantyCardRequest;
use App\Models\Brand;
use App\Models\CommonConfig;
use App\Models\Upload;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WarrantyCard;
use App\Utility\CustomerBillUtility;
use Illuminate\Http\Request;

class WarrantyCardController extends Controller
{
    //
    function  index(Request $request){
        $search=null;
        $status=null;
         $warranty_cards=WarrantyCard::orderBy('created_at','desc');
        if($request->search){
            $search=$request->search;
            $warranty_cards =$warranty_cards->where('user_name','like','%'.$request->search.'%')
            ->orWhere('seri','like','%'.$request->search.'%');
        }

        if((isset($request->sort_status) ? $request->sort_status : -1) >= 0){
            $status=$request->sort_status;
            $warranty_cards =$warranty_cards->where('status',$status);
        }

        $warranty_cards=$warranty_cards->with('brand','uploads')->get();

        return view('backend.customer.warranty_cards.index',compact('warranty_cards','search','status'));

    }

    public function ban($id,Request $request)
    {

            $WarrantyCard = WarrantyCard::with('brand')->findOrFail(decrypt($id));

            $commonConfig=CommonConfig::first();
            $user=User::find($WarrantyCard->user_id);
            $content="";
        if ($request->status==1) {
            $WarrantyCard->status = 1;
            $WarrantyCard->active_time=date('H:i:s');

            $wallet= Wallet::where('user_id',$WarrantyCard->user_id)->first();
            $amount=config_base64_decode( $wallet->amount);
            $point=$amount+(int)$commonConfig->for_activator;

            $wallet->amount=config_base64_encode($point);
            $wallet->save();

            $user->balance=$user->balance+(int)$commonConfig->for_activator;
            $user->save();
            $content="Yêu cầu bảo hành thiết bị ".$WarrantyCard->brand->name." của bạn đã được  phê duyệt .Liện hệ với BQT để biết thêm chi tiết";
            flash(translate('Thẻ đã được kích hoạt thành công'))->success();

            $data=['type'=>CustomerBillUtility::TYPE_LOG_ADDITION,
                'point'=>(int)$commonConfig->for_activator,
                'amount'=>(int)$commonConfig->for_activator*$commonConfig->exchange,
                'object'=>0,
                'amount_first'=>(int)$amount,
                'amount_later'=>(int)config_base64_decode($wallet->amount),
                'user_id'=>$user->id,
                'content'=>"Thẻ bảo hành của khách hàng $WarrantyCard->user_name được kích hoạt"
            ];
            log_history($data);
        } else {
            $WarrantyCard->status = 2;
            $content="Yêu cầu bảo hành thiết bị ".$WarrantyCard->brand->name." của bạn đã đã bị hủy .Liện hệ với BQT để biết thêm chi tiết";
            $WarrantyCard->reason=$request->reason;
            flash(translate('Thẻ đã được hủy'))->warning();
        }
        NewNotification('warranty',$content,$user->id);
        $WarrantyCard->save();

        return back();
    }

    function create(){
        $brands=Brand::select('id','name','status')->get();
        return view('backend.customer.warranty_cards.create',compact('brands'));
    }

    function  store(WarrantyCardRequest $request ){
        $Warranty= new WarrantyCard;
        $Warranty->user_name = $request->user_name;
        $Warranty->address = $request->address;
        $Warranty->seri = $request->seri;
        $Warranty->brand_id = $request->brand_id;
//        $Warranty->qr_code_image = $request->qr_code_image;
//        $Warranty->seri_image = $request->seri_image;
        $Warranty->save();
        uploadMultipleImage($request->image,$Warranty->id,$path='uploads/warranty');

        flash(translate('Card has been add new successfully'))->success();
        return redirect()->route('warranty_card.index');
    }

    function  show($id){

        $warranty_card=WarrantyCard::with('brand','uploads')->findOrFail(decrypt($id));
        return view('backend.customer.warranty_cards.show',compact('warranty_card'));
    }


    function  edit($id){
        $brands=Brand::select('id','name','status')->get();
        $Warranty=WarrantyCard::findOrFail(decrypt($id));
        return view('backend.customer.warranty_cards.edit',compact('Warranty','brands'));
    }


    function  update(WarrantyCardRequest $request, $id){
        $Warranty=WarrantyCard::findOrFail(decrypt($id));
        $Warranty->user_name = $request->user_name;
        $Warranty->address = $request->address;
        $Warranty->brand_id = $request->brand_id;
        $Warranty->qr_code_image = $request->qr_code_image;
        $Warranty->seri_image = $request->seri_image;
        $Warranty->seri = $request->seri;
        $Warranty->save();

        flash(translate('Card has been deleted successfully'))->success();
        return back();
    }



    public function destroy($id)
    {
        $WarrantyCard = WarrantyCard::findOrFail(decrypt($id));

        $uploads=Upload::where('object_id',$WarrantyCard->id)->get();
        foreach ($uploads as $upload){
            if (file_exists(base_path('public/').$upload->file_name)) {
                unlink(base_path('public/') . $upload->file_name);
            }
            $upload->delete();
        }
        $WarrantyCard->delete();
        flash(translate('Card has been deleted successfully'))->success();
        return back();
    }

}
