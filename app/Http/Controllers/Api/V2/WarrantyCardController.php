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
        return new WarrantyCardCollection(WarrantyCard::all());
    }

// i test req from FormData in javascript
    function  store(WarrantyCardRequest $request){



        $idUpload= uploadImageURL($request->seri_image);
        $warrantyCard=new WarrantyCard;
        $warrantyCard->user_name=$request->user_name;
        $warrantyCard->address=$request->address;
        $warrantyCard->seri=$request->seri;
        $warrantyCard->brand_id=$request->brand;
        $warrantyCard->seri_image=$idUpload;
        if($request->qr_code_image!=null){
            $idUpload= uploadImageURL($request->qr_code_image);
            $warrantyCard->qr_code_image=$idUpload;
        }
        $warrantyCard->save();

        return response([
            'result'=>true,
            'message'=>'Card added successfully, please wait about 24 hours for a response'
        ]);
    }
}
