<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Requests\Api\V2\WarrantyCard\WarrantyCardRequest;
use App\Http\Resources\V2\WarrantyCard as WarrantyCardCollection;
use App\Models\Color;
use App\Models\WarrantyCard;
use App\Models\WarrantyCardDetail;
use App\Models\WarrantyCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\UploadFileService;
class WarrantyCardController extends Controller
{
    protected $UploadFileService;
    function __construct(UploadFileService $UploadFileService)
    {
        $this->UploadFileService=$UploadFileService;
    }

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
            $warranty->cardDetail=collect($warranty->cardDetail)->transform(function ($query){
                $query->makeHidden(['created_at', 'updated_at']);
                $query->image = static_asset($query->image);
                $query->color->warranty_duration = $query->color?$query->color->warranty_duration:null;
                $query->warranty_duration=timeWarranty($query->warranty_duration);
                return $query;
            });
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
       $warrantyCode=WarrantyCode::query()->where('code',$request->warranty_code)->first();
        if($warrantyCode && $warrantyCode->status===1){
            return $this->sendError('Mã bảo hành đã được sử dụng');
        }
        $validate=Validator::make($request->all(),[
            'warranty_code'=>'required|exists:warranty_codes,code'
        ],[
           'warranty_code.required'=>'không được để trống',
            'warranty_code.exists'=>'Mã bảo hành không tồn tại',
        ]);
        if($validate->fails()){
            return $this->sendError($validate->errors()->first());
        }


        return  $this->sendSuccess(null);
   }

    function store(Request $request)
    {
//        create database warranty
        $Warranty = new WarrantyCard;
        $Warranty->fill($request->except('product'));
        $Warranty->create_time = strtotime(now());
        $Warranty->user_id = auth()->id();
        $Warranty->save();

        foreach ($request->product as $data) {
//upload file
            $image = uploadFile($data['img'], 'uploads/warranty');
            $video = uploadFile($data['video'], 'uploads/warranty');
            $color=Color::query()->findOrFail($data['color']);
// create warranty card detail
            WarrantyCardDetail::query()->create([
                'warranty_card_id' => $Warranty->id,
                'product_id' => $data['id'],
                'qty' => $data['qty'],
                'image' => $image,
                'video' => $video,
                'color_id' => $data['color'],
                'warranty_duration'=>$color->warranty_duration
            ]);
        }
        $warranty_code = WarrantyCode::query()->where('code', $request->warranty_code)->first();

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
