<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\CustomerGroup;
use Illuminate\Http\Request;

class CustomerGroupController extends Controller
{

    public function data($data, $arr){
        $arr[] = [
            'id'=>$data->id,
            'name'=>$data->name,
            'avatar'=>$data->avatar,
            'description'=>$data->description,
            'bonus'=>$data->bonus,
            'default'=>$data->default,
            'status'=>$data->status,
            'can_withdraw'=>$data->can_withdraw,
            'point_number'=>$data->point_number,
            'created_at' => date('d-m-Y h:i:s', strtotime($data->created_at)),
            'updated_at' => date('d-m-Y h:i:s', strtotime($data->updated_at)),
        ];
        return $arr;
    }

    public function index(){
            $customer_groups = CustomerGroup::all();
            $arr = [];
            foreach ($customer_groups as $key=>$item){
                $data[] = $this->data($item, $arr);
            }
            return response()->json([
                'result' => true,
                'data' => $data,
            ]);
    }

    public function show($id){
        $customer_group = CustomerGroup::where('id', $id)->first();
        $data = [];
        if($customer_group == null){
            return response()->json([
                'result' => false,
                'message' => 'Nhóm người dùng không tồn tại !',
            ]);
        };
        $data[] = $this->data($customer_group, $data);
        return response()->json([
            'result' => true,
            'data' => $data,
        ]);
    }
}
