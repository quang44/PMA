<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\CustomerGroup;
use Illuminate\Http\Request;

class CustomerGroupController extends Controller
{
    public function list_cus_gr(){
            $data = CustomerGroup::all();
            return response()->json([
                'result' => true,
                'data' => $data,
            ]);
    }
}
