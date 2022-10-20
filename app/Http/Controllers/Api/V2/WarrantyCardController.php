<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\WarrantyCard\WarrantyCardRequest;
use App\Http\Resources\V2\WarrantyCard as WarrantyCardCollection;
use App\Models\Upload;
use Illuminate\Support\Facades\File;
use App\Models\WarrantyCard;
use Illuminate\Http\Request;

class WarrantyCardController extends Controller
{
    function  index(){
        return new WarrantyCardCollection(WarrantyCard::with('brand','uploads')->get());
    }

    function search(Request $request){
        $warranty=WarrantyCard::with('brand','uploads')
            ->where('user_name','like','%'.$request->user_name.'%')
            ->get();
        return new WarrantyCardCollection($warranty);
    }

    function show($id){
        $warranty=WarrantyCard::where('id',$id)->get();
        return new WarrantyCardCollection($warranty);
    }


// i test req from FormData in javascript
    function  store(WarrantyCardRequest $request){

        $warrantyCard=new WarrantyCard;
        $warrantyCard->user_id=auth()->user()->id;
        $warrantyCard->user_name=$request->user_name;
        $warrantyCard->address=$request->address;
        $warrantyCard->seri=$request->seri;
        $warrantyCard->brand_id=$request->brand;
        $warrantyCard->active_time=date('Y-m-d H:i:s');

//        $warrantyCard->seri_image=$idUpload;
//        if($request->qr_code_image!=null){
//            $idUpload= uploadImageURL($request->qr_code_image);
//            $warrantyCard->qr_code_image=$idUpload;
//        }
        $warrantyCard->save();
        $id=$warrantyCard->id;
        uploadMultipleImage($request->image,$id,$path='uploads/warranty');


        return response([
            'result'=>true,
            'message'=>'Card added successfully, please wait about 24 hours for a response'
        ]);
    }
}
