<?php

namespace App\Http\Controllers;

use App\Models\WarrantyCard;
use Illuminate\Http\Request;

class WarrantyCardController extends Controller
{
    //
    function  index(Request $request){
        $search=null;
         $warranty_cards=WarrantyCard::query();
        if($request->has('search')){
            $search=$request->search;
            $warranty_cards =$warranty_cards->where('user_name','like','%'.$request->search.'%')
            ->orWhere('seri','like','%'.$request->search.'%');
        }
        $warranty_cards=$warranty_cards->with('brand')->get();

        return view('backend.customer.warranty_cards.index',compact('warranty_cards','search'));

    }
}
