<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\LogCollection;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    function index(Request $request){
            $logHistory=Log::where('user_id',auth()->id());
            if($request->filter!=null?$request->filter:-1 > -1){
                $logHistory=   $logHistory->where('type',$request->type);
            }


           $logHistory= $logHistory->orderBy('id','DESC')->paginate($request->limit??15);
            return new LogCollection($logHistory);

    }
}
