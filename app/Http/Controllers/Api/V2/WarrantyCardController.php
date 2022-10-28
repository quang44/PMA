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
        $warrantyCard=WarrantyCard::with('brand','uploads')
            ->where('user_id',auth()->id())->latest()->paginate(15);
        return new WarrantyCardCollection($warrantyCard);
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

    function delete($id){
        WarrantyCard::findOrFail($id)->delete();
        return [
            'result'=>true,
            'message'=>"you have successfully deleted the warranty card"
        ];
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

        $warrantyCard->save();
        $id=$warrantyCard->id;
        uploadMultipleImage($request->image,$id,$path='uploads/warranty');


        return response([
            'result'=>true,
            'message'=>'Card added successfully'
        ]);
    }


    function  update(WarrantyCardRequest $request,$id){

        $warrantyCard= WarrantyCard::with('uploads')->findOrFail($id);
        $warrantyCard->user_id=auth()->user()->id;
        $warrantyCard->user_name=$request->user_name;
        $warrantyCard->address=$request->address;
        $warrantyCard->seri=$request->seri;
        $warrantyCard->brand_id=$request->brand;
        $warrantyCard->active_time=date('Y-m-d H:i:s');
        $warrantyCard->save();
        $id=$warrantyCard->id;
        if($request->image){
            foreach ($warrantyCard->uploads as $upload){
                if(file_exists(public_path('').$upload->file_name)){
                    unlink(public_path('').$upload->file_name);
                }
            }
                uploadMultipleImage($request->image,$id,$path='uploads/warranty');

        }


        return response([
            'result'=>true,
            'message'=>'Card added successfully'
        ]);
    }

}
