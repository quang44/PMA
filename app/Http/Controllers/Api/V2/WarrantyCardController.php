<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\WarrantyCard as WarrantyCardCollection;
use App\Models\Color;
use App\Models\WarrantyCard;
use App\Models\WarrantyCardDetail;
use App\Models\WarrantyCode;
use App\Services\UploadFileService;
use App\Utility\WarrantyCardUtility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

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
        $warranty->makeHidden(['created_at', 'updated_at','card_detail']);

        if ($warranty) {
            $warranty->create_time = convertTime($warranty->create_time);
            $warranty->active_time =$warranty->active_time?convertTime($warranty->active_time):null;

            $project_photo=collect(explode(',',$warranty->project_photo))->transform(function ($query){
                return static_asset($query);
            });
            $warranty->project_photo=$project_photo->toArray();


            $warranty->cardDetail=collect($warranty->cardDetail)->transform(function ($query) {
                $query->makeHidden(['created_at', 'updated_at']);
                $image=explode(',',$query->image);
                $image=collect($image)->transform(function ($query){
                   return static_asset($query);
                });

                $query->image = $image->toArray();
//                $query->video = static_asset($query->video);
//                $query->color->warranty_duration = $query->color?$query->color->warranty_duration:null;
                $query->warranty_duration = timeWarranty($query->warranty_duration);
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
//        a


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

//     return $this->sendSuccess($request->product[0]['img']);
        $Warranty = new WarrantyCard;
        $Warranty->fill($request->except('product'));
        $Warranty->create_time = strtotime(now());
        $Warranty->user_id = auth()->id();
        $Warranty->warranty_code=$request->warranty_code;
        $Warranty->latlng=  $request->lat.','.$request->lng;
         $imgs=$request->file('project_photo');
        $dataImage=[];
            foreach ($imgs as $img){
                $dataImage[]= uploadFile($img,'uploads/warranty');
            }
//            dd($dataImage);
//            $photo= Image::make($request->file('project_photo'))->sharpen(10);
//            $photo->save( $destinationPath = public_path('uploads/warranty').'/'.$img->hashName());
//            $newPath = 'uploads/warranty/'.$img->hashName();
            $implode=implode(',',$dataImage);
            $Warranty->project_photo=$implode;
               $Warranty->save();


        foreach ($request->product as $data) {
//upload file
//            return $this->sendSuccess($data);
            $image=[];
            if(isset($data['img']) && !empty($data['img'])){
                foreach ($data['img'] as $img){
                    $image[]= uploadFile($img,'uploads/warranty');
                }
            }
            $implode=implode(',',$image);
//            $video = uploadFile($data['video'], 'uploads/warranty');
            $color=Color::query()->findOrFail($data['color']);
// create warranty card detail
            WarrantyCardDetail::query()->create([
                'warranty_card_id' => $Warranty->id,
                'product_id' => $data['id'],
                'qty' => $data['qty'],
                'image' => $implode,
//                'video' => $video,
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
        $Warranty->latlng=  $request->lat.','.$request->lng;
        $project_photo=$Warranty->project_photo;
        if(!empty($request->project_photo)){
            $imgs=$request->file('project_photo');
            $dataImage=[];
            foreach ($imgs as $img){
                $dataImage[]= uploadFile($img,'uploads/warranty');
            }
            $implode=implode(',',$dataImage);
            $Warranty->project_photo=$implode;
        }
        $Warranty->save();

        foreach ($request->product as $data) {
            if(!isset($data['card_id'])){
                $warrantyDetail=new WarrantyCardDetail;
                $dataImg=[];
                if(isset($data['img']) && !empty($data['img'])){
                    foreach ($data['img'] as $img){
                        $dataImg[]=  uploadFile($img, 'uploads/warranty');
                    }
                }
            }else{
                $warrantyDetail = WarrantyCardDetail::query()
                    ->where('warranty_card_id', $id)
                    ->where('id',$data['card_id'])->first();
                if (isset($data['img']) && !empty($data['img']) ) {
                    if($warrantyDetail->image){
                        $dataImg=explode(',',$warrantyDetail->image);
                        foreach ($dataImg as $img){
                            removeImg($img);
                        }
                    }
                    $dataImg=[];
                    foreach ($data['img'] as $img){
                        $dataImg[]= uploadFile($img, 'uploads/warranty');
                    }
                }
                else {
                    $dataImg = $warrantyDetail->image;
                }

//                if (isset($data['video'])) {
//                    removeImg($warrantyDetail->video);
//                    $video = uploadFile($data['video'], 'uploads/warranty');
//                } else {
//                    $video = $warrantyDetail->video;
//                }
            }
//dd(is_array($dataImg));
            if(is_array($dataImg)==true){
                $implode=implode(',',$dataImg);
            }else{
                $implode=$dataImg;
            }
//            $implode=implode(',',$dataImg);
            $warrantyDetail->warranty_card_id=$Warranty->id;
            $warrantyDetail->product_id=$data['id'];
            $warrantyDetail->qty=$data['qty'];
            $warrantyDetail->image=$implode;
//            $warrantyDetail->video=$video;
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

    function warranty_lookup($phone)
    {
        $warrantyCard = WarrantyCard::query()
            ->with(['code', 'user', 'district', 'ward', 'province', 'cardDetail.product', 'cardDetail.color','active_user_id'])
            ->where('phone',$phone);
        $warrantyCard = $warrantyCard->orderByDesc('updated_at')->get();

        $warrantyCard=collect($warrantyCard)->transform(function ($query){
            $query->makeHidden(['card_detail','created_at','updated_at','note','active_user_id']);

            $project_photo=collect(explode(',',$query->project_photo))->transform(function ($query){
                return $query?static_asset($query):null;
            });
            $query->project_photo=$project_photo->toArray();
            $query->accept_by=$query->active_user_id->name??null;
            $query->create_time=date('d-m-y H:i:s',$query->create_time);
            $query->active_time=$query->active_time? date('d-m-y H:i:s',$query->active_time):'--';
            $query->cardDetail=collect(  $query->cardDetail)->transform(function ($q){
                $q->warranty_duration=timeWarranty( $q->warranty_duration);
//                $q->image=static_asset($q->image);
                $image=explode(',',$q->image);
                $image=collect($image)->transform(function ($query){
                    return static_asset($query);
                });
                $q->image = $image->toArray();
                $q->status=WarrantyCardUtility::$aryStatus[$q->status]==2?"đã hủy /$q->reason":WarrantyCardUtility::$aryStatus[$q->status];

                $q->video=static_asset($q->video);
                return $q;
            });

            return $query;
        });
        return response([
            'return' => true,
            'data' => $warrantyCard
        ]);

    }





}
