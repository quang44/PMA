<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\WarrantyBillCollection;
use App\Models\WarrantyCard;
use Illuminate\Http\Request;

class WarrantyCardController extends Controller
{
    function  index(){
        return new WarrantyBillCollection(WarrantyCard::all());
    }
}
