<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\WarrantyBill\WarrantyRequest;
use App\Http\Resources\V2\WarrantyBillCollection;
use App\Models\WarrantyCode;
use Illuminate\Http\Request;

class WarrantyBillController extends Controller
{

    public function store(WarrantyRequest $request)
    {
        $warrantyBill=new WarrantyCode();
        $warrantyBill->fill($request->all());
        $warrantyBill->save();
        return response([
            'message'=>translate('Your request has been sent successfully'),
            'result'=>true,
        ]);

    }

    public  function  index(){
        return new WarrantyBillCollection(WarrantyCode::all());
    }

}
