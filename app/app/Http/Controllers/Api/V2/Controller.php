<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendSuccess($data, $code = 200,$message = 'Successfully')
    {
        return response()->json([
            'result'=>true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function createSuccess($data,$message='Successfully')
    {
        return $this->sendSuccess($data, 201,$message);
    }

    public function updateSuccess($data,$message='Successfully')
    {
        return $this->sendSuccess($data, 201,$message);
    }

    public function deleteSuccess($message="Delete Successfully")
    {
        return response()->json([
            'result'=>true,
            'message'=>$message
        ],200);
    }


    public function sendError( $message = 'Errors',$code = 200)
    {
        return response()->json([
            'result'=>false,
            'message' => $message,
        ], $code);
    }

}
