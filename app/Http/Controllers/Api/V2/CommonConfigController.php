<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\CommonConfig;
use Illuminate\Http\Request;

class CommonConfigController extends Controller
{
    public function index()
    {
        $common_config = CommonConfig::first();
        $data = [];
        $data[] = [
            'logo' => $common_config->logo,
            'unit' => $common_config->unit,
            'for_referrer' => $common_config->unit,
            'for_activator' => $common_config->unit,
            'contact_info' => $common_config->contact_info,
            'created_at' => date('d-m-Y h:i:s', strtotime($common_config->created_at)),
            'updated_at' => date('d-m-Y h:i:s', strtotime($common_config->updated_at)),
        ];
        return response()->json([
            'result' => true,
            'data' => $data
        ]);
    }
}
