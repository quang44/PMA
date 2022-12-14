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
        $WarrantyCard->reason = "Kh??ch h??ng t??? h???y y??u c???u";
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
           'warranty_code.required'=>'kh??ng ???????c ????? tr???ng',
            'warranty_code.unique'=>'M?? b???o h??nh ???? ???????c s??? d???ng',
            'warranty_code.exists'=>'M?? b???o h??nh kh??ng t???n t???i',
        ]);
        if($validate->fails()){
            return $this->sendError($validate->errors()->first());
        }
        return  $this->sendSuccess(null);
   }

    function store(Request $request)
    {


//      echo '<pre>';
//      print_r($request->all());
//      echo '</pre>';
//      die();
//         check warranty code exits

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

//            $image= $this->UploadFileService->uploadFile($data['img'],'uploads/warranty');
//            $video= $this->UploadFileService->uploadFile($data['video'],'uploads/warranty');
//             $image= $Warranty->uploadFile($data['img'],'uploads/warranty');
//             $video= $Warranty->uploadFile($data['video'],'uploads/warranty');


// create warranty card detail
            WarrantyCardDetail::create([
                'warranty_card_id' => $Warranty->id,
                'product_id' => $data['id'],
                'qty' => $data['qty'],
                'image' => $image,
                'video' => $video,
                'color_id' => $data['color'],
            ]);
        }


        $warranty_code = WarrantyCode::query()->where('code', $request->warranty_code)->first();
// update status  warranty code
        if ($warranty_code) {
            $warranty_code->status = 1;
            $warranty_code->use_at = now();
            $warranty_code->save();
        }


//        Th??? b???o h??nh c???a b??? ???? ???????c t???o th??nh c??ng
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
