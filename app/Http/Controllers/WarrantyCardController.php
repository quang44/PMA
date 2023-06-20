<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarrantyCardRequest;
use App\Models\Brand;
use App\Models\Color;
use App\Models\CommonConfig;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Province;
use App\Models\Upload;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WarrantyCard;
use App\Models\WarrantyCardDetail;
use App\Models\WarrantyCode;
use App\Utility\CustomerBillUtility;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class WarrantyCardController extends Controller
{
    //
      function  index(Request $request){
        $search=null;
        $status=null;
        $sort_customer=null;
         $warranty_cards=WarrantyCard::orderBy('updated_at','desc');

        if((isset($request->sort_customer) ? $request->sort_customer : -1) >= 0){
            $sort_customer=$request->sort_customer;
            $warranty_cards =$warranty_cards->where('user_id',$sort_customer);
        }

        if((isset($request->sort_status) ? $request->sort_status : -1) >= 0){
            $status=$request->sort_status;
            $warranty_cards =$warranty_cards->where('status',$status);
        }

        if($request->search){
            $search=$request->search;
            $warranty_cards =$warranty_cards->where('user_name','like','%'.$search.'%')
                ->orWhere('phone','like','%'.$search.'%');
//                ->orWhere('address','like','%'.$search.'%');
        }


        $customers = User::whereIn('id', function($query) {
            $query->select('user_id')->from(with(new WarrantyCard)->getTable());
        })->get();

        $warranty_cards=$warranty_cards->with('user','cardDetail.product','active_user_id','district','province','ward')->paginate(15);
        return view('backend.customer.warranty_cards.index',compact('warranty_cards','search','status','customers'));

    }

    public function ban($id,Request $request)
    {



        $WarrantyCard = WarrantyCard::with('brand','cardDetail')->findOrFail(decrypt($id));
            if(!$WarrantyCard->cardDetail){
                flash(translate('Lỗi không tìm thấy cửa được bảo hành'))->warning();
                return back();
            }
//            dd($product);
        $cardetail=$WarrantyCard->cardDetail->toArray();
        $cardetail=collect($cardetail)->transform(function ($item){
              $item['product']['qty']=$item['qty'];
              $item['product']['status']=$item['status'];
             return $item;
        });

        $product=array_column($cardetail->toArray(),'product');

        $product=array_filter($product,function ($item){
           return $item['status']==0;
        });
        $point=collect($product)->reduce(function ($init,$item)  {
            return   $init+=(int)$item['unit']*(int)$item['qty'];
            },0);

            $commonConfig=CommonConfig::first();
            $user=User::find($WarrantyCard->user_id);
            if(!$user){
                flash(translate('Lỗi không tìm thấy người tạo'))->warning();
                return back();
            }
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
            $amount=config_base64_decode($wallet->amount);
            $wallet->amount=config_base64_encode($amount+$point);
            $wallet->updated_at=date('Y-m-d H:i:s');
            $wallet->save();

//            $user->balance=$user->balance+(int)$commonConfig->for_activator;
//            $user->save();

            $content="Bạn đã được +$point điểm do kích hoạt thẻ bảo hành thành công của khách hàng $WarrantyCard->user_name  ";

            log_history(['type'=>CustomerBillUtility::TYPE_LOG_ADDITION,
                'point'=>(int)$point,
                'amount'=>(int)$point*$commonConfig->exchange,
                'object'=>0,
                'amount_first'=>(int)$amount,
                'amount_later'=>(int)config_base64_decode($wallet->amount),
                'user_id'=>$user->id,
                'accept_by'=>auth()->id(),
                'content'=>$content
            ]);
          WarrantyCardDetail::query()->where('warranty_card_id',decrypt($id))
               ->where('status',0)->update(['status'=>1]);

            flash(translate('Thẻ đã được kích hoạt thành công'))->success();
        } else {
            $WarrantyCard->status = 2;
            $content="Yêu cầu bảo hành thiết bị của bạn đã bị hủy ";
            $WarrantyCard->reason=$request->reason;

            WarrantyCardDetail::query()->where('warranty_card_id',decrypt($id))
                ->where('status',0)->update(['status'=>2]);

            flash(translate('Thẻ đã được hủy thành công'))->warning();
        }
        $WarrantyCard->save();
//        update_customer_package($user->id);
        sendFireBase($user, "Kích hoạt thẻ bảo hành !", $content, 'warranty', $amount, config_base64_decode($wallet->amount), auth()->id(), $WarrantyCard);
        return back();
    }


    public function ban_detail($id,Request $request)
    {
        $WarrantyCardDetail = WarrantyCardDetail::query()->findOrFail(decrypt($id));
        $WarrantyCardDetail->load('product');
        $WarrantyCardDetail->status=2;
        $WarrantyCardDetail->reason=$request->reason;
        $WarrantyCard=WarrantyCard::query()->find($WarrantyCardDetail->warranty_card_id);
        $WarrantyCardDetail->save();
        $user=User::query()->find($WarrantyCard->user_id);
        if(!$user){
            flash(translate('Lỗi không tìm thấy người tạo'))->warning();
            return back();
        }
            $product_name=$WarrantyCardDetail->product->name??'';
            $content="Sản phẩm $product_name trong thẻ bảo hành của Khách hàng $WarrantyCard->user_name đã bị hủy ";
            flash(translate('Thẻ đã được hủy thành công'))->warning();
            $wallet= Wallet::where('user_id',$WarrantyCard->user_id)->first();
        if(!$wallet){
            $wallet=Wallet::create([
                'user_id'=>$user->id,
            ]);
        }
           $amount=config_base64_decode($wallet->amount);
//        update_customer_package($user->id);
        sendFireBase($user, "Kích hoạt thẻ bảo hành !", $content, 'warranty', $amount, config_base64_decode($wallet->amount), auth()->id(), $WarrantyCardDetail);
        return back();
    }

    function create(){
        $customers= User::where('banned',0)->orderBy('created_at','desc')->get();
        $products=Product::select('*')
            ->where('wholesale_product',1)
            ->orderBy('created_at','DESC')->get();
        $colors=Color::all()->pluck('name','id');
        $provinces=Province::all();
        return view('backend.customer.warranty_cards.create',compact('provinces','products','customers','colors'));
    }

    function  store(WarrantyCardRequest $request ){

        $warranty_code=WarrantyCode::where('code',$request->warranty_code)->first();
        // user_id, user_name,phone,address,video_url,warranty_code
        $Warranty= new WarrantyCard;
        $Warranty->fill($request->all());
        $Warranty->warranty_code=$request->warranty_code;
        $Warranty->create_time = strtotime(now());
        $Warranty->latlng=  $request->lat.','.$request->long;
        if($request->hasFile('project_photo')){
            $imgs=$request->file('project_photo');
            $dataImage=[];
            foreach ($imgs as $img){
                $dataImage[]= uploadFile($img,'uploads/warranty');
            }
            $implode=implode(',',$dataImage);

            $Warranty->project_photo=$implode;

//            $img=$request->file('project_photo');
//            $photo= Image::make($request->file('project_photo'))->sharpen(10);
//            $photo->save( $destinationPath = public_path('uploads/warranty').'/'.$img->hashName());
//            $newPath = 'uploads/warranty/'.$img->hashName();
//            $Warranty->project_photo=$newPath;
        }
        $Warranty->save();

        $WarrantyDetail= new WarrantyCardDetail;
        foreach ($request->product as $key=>$data){
//            dd($request->all());
            $image=[];
            if($request->image[$key]){
                $upload=Upload::query()->whereIn('id',explode(',',$request->image[$key]))->get();
                foreach ($upload as $img){
                    $image[]=$img['file_name'];
                }
            }
            $color=Color::query()->findOrFail($request->color[$key]);
            $WarrantyDetail->create([
                'warranty_card_id'=>$Warranty->id,
                'product_id'=>$data,
                'qty'=>$request->qty[$key],
                'image'=>implode(',',$image),
                'color_id'=>$request->color[$key],
                'warranty_duration'=>$color->warranty_duration
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
        flash('Thẻ đã được xóa thành công')->success();
        return back();
    }

    function buck_delete(Request $request){
        if ($request->id) {
            foreach ($request->id as $warranty_id) {
                $warrantyCard = WarrantyCard::find($warranty_id);
                WarrantyCardDetail::where('warranty_card_id',$warrantyCard->id)->delete();
                $warrantyCard->delete();
            }
        }
        return 1;
    }


    public function cancel($id)
    {
        $warrantyCard = WarrantyCard::findOrFail(decrypt($id));
        $warrantyCard->status=2;
        flash(translate('Card has been cancel successfully'))->success();
        return back();
    }


       public function card_combination(){
         $products=Product::select('*')
             ->where('wholesale_product',1)
             ->orderBy('created_at','DESC')->get();
         $colors=Color::all()->pluck('name','id');
        return view('backend.customer.warranty_cards.combinations',compact('products','colors'));
    }

    public function card_combination_edit(Request $request){
        $key=$request->post('key');
        $products=Product::select('*')->orderBy('created_at','DESC')->get();
        return view('backend.customer.warranty_cards.combinations_edit',compact('key','products'));
    }

    public function edit_qty(Request $request,$id){
    $warrantyCard=WarrantyCardDetail::find($id);
    $warrantyCard->qty=$request->qty;
    $warrantyCard->save();
    flash()->success('Cập nhật số lượng thành công');
    return back();
    }
}
