<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Requests\Api\V2\WarrantyCard\WarrantyCardRequest;
use App\Http\Resources\V2\WarrantyCard as WarrantyCardCollection;
use App\Models\WarrantyCard;
use App\Models\WarrantyCardDetail;
use App\Models\WarrantyCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WarrantyCardController extends Controller
{
    function index(Request $request)
    {
        $warrantyCard = WarrantyCard::query()->where('user_id', auth()->user()->id);
        if (!empty($request->search)) {
            $warrantyCard = $warrantyCard->where('phone', 'like', "%$request->search%")
                ->orWhere('user_name', 'like', "%$request->search%");
        }
        $warrantyCard = $warrantyCard->orderByDesc('updated_at')
            ->paginate($request->limit ?? 10);
//        $warrantyCard = $warrantyCard->makeHidden('created_at', 'updated_at');

        return new WarrantyCardCollection($warrantyCard);
    }

    function warrantyCode()
    {
        $code = WarrantyCode::query()->where('status',0)->get();
        $code = $code->makeHidden(['created_at', 'updated_at']);
        return $this->sendSuccess($code);
    }


    function show($id)
    {
        $warranty = WarrantyCard::query()->with(['code', 'user', 'district', 'ward', 'province', 'cardDetail.product', 'cardDetail.color'])->findOrFail($id);
        $warranty->makeHidden(['created_at', 'updated_at']);
        if ($warranty) {
            $warranty->create_time = convertTime($warranty->create_time);
            $warranty->active_time = convertTime($warranty->active_time);
            foreach ($warranty->cardDetail as $item) {
                $item->image = static_asset($item->image);
                $item->video = static_asset($item->video);
                $item->color->warranty_duration = $item->color->warranty_duration;
            }
        }
        return $this->sendSuccess($warranty);
    }


    function destroy($id)
    {
        $WarrantyCard = WarrantyCard::findOrFail($id);
        $WarrantyCard->reason = "Khách hàng tự hủy yêu cầu";
        $WarrantyCard->active_time = strtotime(Carbon::now());
        $WarrantyCard->status = 2;
        $WarrantyCard->save();

//        $uploads=Upload::where('object_id',$WarrantyCard->id)->get();
//        foreach ($uploads as $key=>$upload){
//            if (file_exists(base_path('public/').$upload->file_name)) {
//                unlink(base_path('public/') . $upload->file_name);
//            }
//            $upload->delete();
//        }

//        $WarrantyCard->delete();

        return $this->deleteSuccess();
    }

   function validateWarrantyCard(Request $request){
        $validate=Validator::make($request->all(),[
            'warranty_code'=>'required|unique:warranty_cards,warranty_code|exists:warranty_codes,code'
        ],[
           'warranty_code.required'=>'không được để trống',
            'warranty_code.unique'=>'Mã bảo hành đã được sử dụng',
            'warranty_code.exists'=>'Mã bảo hành không tồn tại',
        ]);
        if($validate->fails()){
            return $this->sendError($validate->errors()->first());
        }
        return  $this->sendSuccess(null);
   }

    function store(WarrantyCardRequest $request)
    {

//         check warranty code exits
        $warranty_code = WarrantyCode::query()->where('code', $request->warranty_code)->first();

//        create database warranty

        $Warranty = new WarrantyCard;
        $Warranty->fill($request->all());
        $Warranty->create_time = strtotime(now());
        $Warranty->user_id = auth()->id();
        $Warranty->save();

// create warranty card detail
        foreach ($request->product as $data) {

            $image = uploadFile($data['img'], 'uploads/warranty');
            $video = uploadFile($data['video'], 'uploads/warranty');
//            dd($video);
            WarrantyCardDetail::query()->insert([
                'warranty_card_id' => $Warranty->id,
                'product_id' => $data['id'],
                'qty' => $data['qty'],
                'image' => $image,
                'video' => $video,
                'color_id' => $data['color'],
            ]);
        }
// update status  warranty code
        if ($warranty_code) {
            $warranty_code->status = 1;
            $warranty_code->use_at = now();
            $warranty_code->save();
        }


//        Thẻ bảo hành của bạ đã được tạo thành công
        return $this->createSuccess($Warranty);
    }


    function update(Request $request, $id)
    {

        $Warranty = WarrantyCard::query()->findOrFail($id);
        $Warranty->fill($request->all());
        $Warranty->create_time = strtotime(now());
        $Warranty->save();


        foreach ($request->product as $data) {

//            check id warranty_card
            if(!isset($data['card_id'])){
                $warrantyDetail=new WarrantyCardDetail;
                $image =  uploadFile($data['img'], 'uploads/warranty');
                $video = uploadFile($data['video'], 'uploads/warranty');
            }else{

                $warrantyDetail = WarrantyCardDetail::query()
                    ->where('warranty_card_id', $id)
                    ->where('id',$data['card_id'])->first();

                if (isset($data['img'])) {
                    removeImg($warrantyDetail->image);
                    $image = uploadFile($data['img'], 'uploads/warranty');
                } else {
                    $image = $warrantyDetail->image;
                }
                if (isset($data['video'])) {
                    removeImg($warrantyDetail->video);
                    $video = uploadFile($data['video'], 'uploads/warranty');
                } else {
                    $video = $warrantyDetail->video;
                }
            }

            $warrantyDetail->warranty_card_id=$Warranty->id;
            $warrantyDetail->product_id=$data['id'];
            $warrantyDetail->qty=$data['qty'];
            $warrantyDetail->image=$image;
            $warrantyDetail->video=$video;
            $warrantyDetail->color_id=$data['color'];
            $warrantyDetail->save();

        }

        return $this->updateSuccess($Warranty);
    }


    function deleteDetail($id)
    {
        WarrantyCardDetail::query()->findOrFail($id)->delete();
        return $this->deleteSuccess();
    }

}
