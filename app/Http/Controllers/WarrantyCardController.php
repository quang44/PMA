<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarrantyCardRequest;
use App\Models\Brand;
use App\Models\WarrantyCard;
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

        $warranty_cards=$warranty_cards->with('brand')->get();

        return view('backend.customer.warranty_cards.index',compact('warranty_cards','search','status'));

    }

    public function ban($id,Request $request)
    {
        $WarrantyCard = WarrantyCard::findOrFail(decrypt($id));

        if ($WarrantyCard->status == 0 || $WarrantyCard->status !=1) {
            $WarrantyCard->status = 1;
            $WarrantyCard->active_time=date('H:i:s');
            $WarrantyCard->reason='';
            flash(translate('Card UnBanned Successfully'))->success();
        } else {
            $WarrantyCard->status = 2;
            $WarrantyCard->reason=$request->reason;
            flash(translate('Card Banned Successfully'))->success();

        }

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
        $Warranty->qr_code_image = $request->qr_code_image;
        $Warranty->seri_image = $request->seri_image;
        $Warranty->save();

        flash(translate('Card has been add new successfully'))->success();
        return redirect()->route('warranty_card.index');
    }

    function  show($id){

        $warranty_card=WarrantyCard::with('brand')->findOrFail(decrypt($id));
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
        $WarrantyCard->destroy();
        flash(translate('Card has been deleted successfully'))->success();
        return back();
    }

}
