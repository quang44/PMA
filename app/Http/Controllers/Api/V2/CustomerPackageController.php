<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\CustomerPackage;
use Illuminate\Http\Request;

class CustomerPackageController extends Controller
{
    public function index()
    {
        $customer_groups = CustomerPackage::all();
        $data = [];
        if ($customer_groups != null) {
            foreach ($customer_groups as $key => $item) {
                $data[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'avatar' => $item->avatar,
                    'description' => $item->description,
                    'bonus' => $item->bonus,
                    'default' => $item->default,
                    'status' => $item->status,
                    'withdraw' => $item->withdraw,
                    'point' => $item->point,
                    'created_at' => date('d-m-Y h:i:s', strtotime($item->created_at)),
                    'updated_at' => date('d-m-Y h:i:s', strtotime($item->updated_at)),
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $customer_package = CustomerPackage::where('id', $id)->first();
        $data = [];
        if ($customer_package == null) {
            return response()->json([
                'result' => false,
                'message' => 'Nhóm người dùng không tồn tại !',
            ]);
        };
        $data[] = [
            'id' => $customer_package->id,
            'name' => $customer_package->name,
            'avatar' => $customer_package->avatar,
            'description' => $customer_package->description,
            'bonus' => $customer_package->bonus,
            'default' => $customer_package->default,
            'status' => $customer_package->status,
            'withdraw' => $customer_package->withdraw,
            'point' => $customer_package->point,
            'created_at' => date('d-m-Y h:i:s', strtotime($customer_package->created_at)),
            'updated_at' => date('d-m-Y h:i:s', strtotime($customer_package->updated_at)),
        ];
        return response()->json([
            'result' => true,
            'data' => $data,
        ]);
    }
}
