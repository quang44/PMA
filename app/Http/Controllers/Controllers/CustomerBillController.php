<?php

namespace App\Http\Controllers;

use App\Models\CustomerBill;
use App\Models\OrderDelivery;
use App\Models\User;
use App\Utility\CustomerBillUtility;
use App\Utility\OrderDeliveryUtility;
use Illuminate\Http\Request;

class CustomerBillController extends Controller
{
    public function index(Request $request){
        $status = CustomerBillUtility::$aryStatus;
        $sort_search = null;
        $orders = CustomerBill::query();
        $orders = $orders->where('status', '!=', CustomerBillUtility::STATUS_CANCEL);
        if ($request->status != null) {
            $orders = $orders->where('status', $request->status);
        }

        if ($request->date != null) {
            $orders = $orders->where('created_time', '>=', strtotime(explode(" to ", $request->date)[0]))->where('created_time', '<=', strtotime(explode(" to ", $request->date)[1]) + 86399);
        }
        if ($request->search != '') {
            /*$sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');*/
            $sort_search = $request->search;
            $orders = $orders->where('id', $sort_search);
            /*where(function ($q) use ($sort_search) {
                $q->where('id', $sort_search)->orWhere('partner_code', $sort_search)->orWhere('source_phone', $sort_search)->orWhere('dest_phone', $sort_search);
            });*/
        }
        $orders->with('user.customer_bank');
        $orders = $orders->paginate(15);
        return view('backend.accounting.customer_bill.index', compact('status', 'sort_search', 'orders'));
    }

    public function show($id){
        $bill = CustomerBill::findOrFail(decrypt($id));
        $status = CustomerBillUtility::$aryStatus;
        $orders = OrderDelivery::where('bill_id', decrypt($id))->get();
        return view('backend.accounting.customer_bill.show', compact('bill', 'status', 'orders'));
    }

    public function create($user_id, Request $request){
        $orders = OrderDelivery::where('status_payment', OrderDeliveryUtility::STATUS_PAYMENT_PENDING)->where('user_id', $user_id)->with('user')->get();
        return view('backend.accounting.customer_bill.create', compact('orders'));
    }

    public function store($user_id, Request $request){
        $ids = $request->id ?? [];
        $orders = OrderDelivery::where('status_payment', OrderDeliveryUtility::STATUS_PAYMENT_PENDING)->where('user_id', $user_id)->whereIn('id', $ids)->get();
        if(count($orders) == 0){
            flash(translate('Order not found'))->error();
            return back();
        }
        \DB::transaction(function () use ($orders, $user_id) {
            $total_cod = $total_fee = 0;
            $order_ids = [];
            $kol_id = $employee_id = $kol_value = $employee_value = 0;
            foreach ($orders as $order){
                $order_ids[] = $order->id;
                $total_cod += $order->collect_amount;
                $total_fee += $order->customer_total_fee;
                $kol_id = $order->kol_id;
                $employee_id = $order->employee_id;
                $kol_value += $order->kol_value;
                $employee_value += $order->employee_value;
            }
            //$total = $total_fee - $total_cod;
            $customer_bill = new CustomerBill();
            $customer_bill->user_id = $user_id;
            $customer_bill->user_id = $user_id;
            $customer_bill->order_ids = $order_ids;
            $customer_bill->created_time = time();
            $customer_bill->status = CustomerBillUtility::STATUS_NEW;
            $customer_bill->total_cod = $total_cod;
            $customer_bill->total_fee = $total_fee;
            $customer_bill->kol_id = $kol_id;
            $customer_bill->kol_value = $kol_value;
            $customer_bill->employee_id = $employee_id;
            $customer_bill->employee_value = $employee_value;
            //$order_control->total = $total;
            $customer_bill->save();
            OrderDelivery::where('status_payment', OrderDeliveryUtility::STATUS_PAYMENT_PENDING)->where('user_id', $user_id)->whereIn('id', $order_ids)->update(['bill_id' => $customer_bill->id, 'status_payment' => OrderDeliveryUtility::STATUS_PAYMENT_CONFIRM]);
        });


        flash(translate('Bill created success'))->success();
        return redirect(route('customer_bill.index')) ;
    }

    public function updatePayment($id){
        $customer_bill = CustomerBill::where('id', $id)->where('status', CustomerBillUtility::STATUS_NEW)->first();
        if(!$customer_bill){
            return response([
                'result' => false,
                'message' => translate('Bill not found')
            ]);
        }
        \DB::transaction(function () use ($customer_bill, $id){
            $customer_bill->status = CustomerBillUtility::STATUS_SUCCESS;
            $customer_bill->payment_time = time();
            $customer_bill->save();
            OrderDelivery::where('status_payment', OrderDeliveryUtility::STATUS_PAYMENT_CONFIRM)->where('bill_id', $id)->update(['status_payment' => OrderDeliveryUtility::STATUS_PAYMENT_SUCCESS]);
        });
        return response([
            'result' => true,
            'message' => translate('Bill update payment success')
        ]);
    }

    public function cancel($id){
        $customer_bill = CustomerBill::where('id', $id)->where('status', CustomerBillUtility::STATUS_NEW)->first();
        if(!$customer_bill){
            return response([
                'result' => false,
                'message' => translate('Bill not found')
            ]);
        }
        \DB::transaction(function () use ($customer_bill, $id){
            $customer_bill->status = CustomerBillUtility::STATUS_CANCEL;
            $customer_bill->save();
            OrderDelivery::where('status_payment', OrderDeliveryUtility::STATUS_PAYMENT_CONFIRM)->where('bill_id', $id)->update(['bill_id' => null, 'status_payment' => OrderDeliveryUtility::STATUS_PAYMENT_PENDING]);
        });
        return response([
            'result' => true,
            'message' => translate('Bill update payment success')
        ]);
    }
}
