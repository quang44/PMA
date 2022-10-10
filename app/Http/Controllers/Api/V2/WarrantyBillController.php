<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\WarrantyBill\WarrantyRequest;
use App\Models\WarrantyBill;
use Illuminate\Http\Request;

class WarrantyBillController extends Controller
{

    public function store(WarrantyRequest $request)
    {
        $warrantyBill=new WarrantyBill();
        $warrantyBill->fill($request->all());
        $warrantyBill->save();
        return response([
            'message'=>translate('Your request has been sent successfully'),
            'result'=>true,
        ]);

    }

}
