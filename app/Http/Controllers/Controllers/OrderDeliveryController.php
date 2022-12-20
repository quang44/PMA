<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\OrderDeliveryLog;
use App\Models\User;
use App\Utility\OrderDeliveryUtility;
use Illuminate\Http\Request;

class OrderDeliveryController extends Controller
{
    public function index(Request $request){

        $status_payment = OrderDeliveryUtility::$aryStatusPayment;
        $status_delivery = OrderDeliveryUtility::$aryStatusDelivery;
        $partner_status_payment = OrderDeliveryUtility::$aryPartnerStatusPayment;
        $sort_search = null;
        $customer_id = -1;
        if(!empty($request->phone)){
            $customer = User::where('phone', $request->phone)->first();
            $customer_id = $customer->id ?? 0;
        }
        $orders = OrderDelivery::query();
        if($customer_id >= 0){
            $orders = $orders->where('user_id', $customer_id);
        }
        if ($request->status_payment != null) {
            $orders = $orders->where('status_payment', $request->status_payment);
        }
        if ($request->partner_status_payment != null) {
            $orders = $orders->where('partner_status_payment', $request->partner_status_payment);
        }
        if ($request->status_delivery != null) {
            $orders = $orders->whereIn('status', $request->status_delivery);
        }

        if ($request->kol_id != null) {
            $orders = $orders->where('kol_id', $request->kol_id);
        }

        if ($request->employee_id != null) {
            $orders = $orders->where('employee_id', $request->employee_id);
        }

        if ($request->date != null) {
            $orders = $orders->where('created_time', '>=', strtotime(explode(" to ", $request->date)[0]))->where('created_time', '<=', (strtotime(explode(" to ", $request->date)[1]) + 86399));
        }
        if ($request->search != '') {
            /*$sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');*/
            $sort_search = $request->search;
            $orders = $orders->where(function ($q) use ($sort_search) {
                $q->where('id', $sort_search)->orWhere('partner_code', $sort_search)/*->orWhere('source_phone', $sort_search)->orWhere('dest_phone', $sort_search)*/;
            });
        }

        $sum_fee = $orders->sum('total_fee');
        $sum_customer_fee = $orders->sum('customer_total_fee');
        $sum_cod = $orders->sum('collect_amount');
        $orders = $orders->orderBy('id', 'DESC');
        $orders->with(['user', 'kol', 'employee']);
        $orders = $orders->paginate(15);

        /*foreach ($orders as $key => $value) {
            $order = \App\Models\Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }*/

        return view('backend.sales.order_delivery.index', compact('orders', 'status_payment', 'status_delivery', 'sort_search', 'partner_status_payment', 'sum_fee', 'sum_cod', 'sum_customer_fee'));
    }

    public function show($id){

        $order = OrderDelivery::findOrFail(decrypt($id));
        $status_payment = OrderDeliveryUtility::$aryStatusPayment;
        $status_delivery = OrderDeliveryUtility::$aryStatusDelivery;
        $partner_status_payment = OrderDeliveryUtility::$aryPartnerStatusPayment;
        $history = OrderDeliveryLog::where('order_delivery_id', decrypt($id))->get();
        return view('backend.sales.order_delivery.show', compact('order', 'status_payment', 'status_delivery', 'history', 'partner_status_payment'));
    }
}
