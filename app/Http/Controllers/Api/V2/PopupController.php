<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\PopupResource;
use App\Models\Popup;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $created = strtotime($user->created_at);
        $day = ceil((time() - $created)/86400);
        if($user->user_type == 'customer'){
            $popup = Popup::where('status', 1)->where('type', 'new_user')->where('day', '>=', $day)->orderBy('created_at', 'DESC')->first();
        }
        if(empty($popup)){
            $popup = Popup::where('status', 1)->where('type', $user->user_type)->where('start_time', '<=', time())->where('end_time', '>=', time())->orderBy('created_at', 'DESC')->first();
        }
        if(!$popup){
            return response([
                'result' => false,
                'data' => null
            ]);
        }
        return new PopupResource($popup);
    }
}
