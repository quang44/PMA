<?php

namespace App\Http\Controllers;

use App\Models\WarrantyBill;
use Illuminate\Http\Request;

class WarrantyBillController extends Controller
{

//    show payment guarantee
    function PaymentWarranty(Request $request){
        $sort_search=null;
        $payment_guarantees=WarrantyBill::orderBy('created_at','desc');

        if ($request->search){
            $sort_search = $request->search;
            $payment_guarantees=  $payment_guarantees->where(function ($q) use ($sort_search){
                $q->where('name', 'like', '%'.$sort_search.'%')->orWhere('phone', 'like', '%'.$sort_search.'%');
            });
        }

        if ((isset($request->status) ? $request->status : -1) >= 0) {
            $payment_guarantees=   $payment_guarantees->where('status',$request->status);
        }

        $payment_guarantees= $payment_guarantees->paginate(15);
        return view('backend.accounting.warranty_bill.index',compact('payment_guarantees','sort_search'));
    }


//    update payment status bảo hành
    function updateWarranty(Request $request ,$id){
        $payment = WarrantyBill::where('id', $id)->where('status', 0)->first();
        if(!$payment){
            return response([
                'result' => false,
                'message' => 'Không tìm thấy yêu cầu cần thanh toán'
            ]);
        }
        $payment->status = 1;
        $payment->save();
        return response([
            'result' => true,
            'message' => 'Cập nhật thanh toán thành công'
        ]);
    }

    //    cancel payment status bảo hành

    public function cancelWarranty($id, Request $request){
        $payment = WarrantyBill::where('id', $id)->where('status', 0)->first();
        if(!$payment){
            return response([
                'result' => false,
                'message' => 'Không tìm thấy yêu cầu cần thanh toán'
            ]);
        }else{
            $payment->reason = $request->reason;
            $payment->status = -1;
            $payment->save();
        }

        return response([
            'result' => true,
            'message' => 'Hủy thanh toán thành công'
        ]);
    }




}
