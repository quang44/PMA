<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\CustomerBillCollection;
use App\Models\CustomerBill;
use App\Models\OrderDelivery;
use App\Utility\CustomerBillUtility;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index(Request $request){
        $bills = CustomerBill::query();
        $bills = $bills->where('user_id', auth()->id())->whereIn('status', [CustomerBillUtility::STATUS_NEW, CustomerBillUtility::STATUS_SUCCESS]);
        if((int)$request->start_time > 0){
            $bills = $bills->where('created_time', '>=', (int)$request->start_time);
        }
        if((int)$request->end_time > 0){
            $bills = $bills->where('created_time', '<=', (int)$request->end_time);
        }
        $bills = $bills->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
        return new CustomerBillCollection($bills);
    }

    public function show($id){
        $bill =  CustomerBill::find($id);
        if(!$bill){
            return response([
                'result' => false,
                'message' => trans('Bill not found')
            ]);
        }
        $orders = OrderDelivery::where('bill_id', $id)->get();
        return response([
            'result' => true,
            'data' => [
                'bill' => $bill,
                'orders' => $orders
            ]
        ]);
    }


}
