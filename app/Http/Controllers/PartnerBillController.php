<?php

namespace App\Http\Controllers;

use App\Models\OrderDelivery;
use App\Models\PartnerBill;
use App\Models\WarrantyBill;
use App\Utility\OrderDeliveryUtility;
use Illuminate\Http\Request;

class PartnerBillController extends Controller
{
    public function index(Request $request){
        $sort_search = null;
        $orders = PartnerBill::query();

        if ($request->date != null) {
            $orders = $orders->where('created_time', '>=', strtotime(explode(" to ", $request->date)[0]))->where('created_time', '<=', strtotime(explode(" to ", $request->date)[1]) + 86399);
        }
        if ($request->search != '') {
            $sort_search = $request->search;
            $orders = $orders->where('partner_bill_id', $sort_search);
        }
        $orders = $orders->paginate(15);
        return view('backend.accounting.partner_bill.index', compact('sort_search', 'orders'));
    }

    public function show($id){
        $bill = PartnerBill::findOrFail(decrypt($id));
        $orders = OrderDelivery::where('partner_bill_id', $bill->partner_bill_id)->get();
        return view('backend.accounting.partner_bill.show', compact('bill', 'orders'));
    }

    public function create(Request $request){
        $sort_search = null;
        $orders = [];
        if ($request->search != '') {
            $sort_search = $request->search;
            $orders_ids = explode(',', $sort_search);
            $orders = OrderDelivery::where('partner_status_payment', OrderDeliveryUtility::PARTNER_STATUS_PAYMENT_NEW)->whereIn('id', $orders_ids)->get();
        }
        return view('backend.accounting.partner_bill.create', compact('orders', 'sort_search'));
    }

    public function store(Request $request){

        $ids = $request->ids;
        $orders_ids = explode(',', $ids);
        $orders = OrderDelivery::where('partner_status_payment', OrderDeliveryUtility::PARTNER_STATUS_PAYMENT_NEW)->whereIn('id', $orders_ids)->get();
        if(count($orders) == 0){
            flash(translate('Order not found'))->error();
            return back();
        }
        $partner_bill_id = $request->partner_bill_id;
        \DB::transaction(function () use ($orders, $partner_bill_id) {
            $total_cod = $total_fee = 0;
            $order_ids = [];
            foreach ($orders as $order){
                $order_ids[] = $order->id;
                $total_cod += $order->collect_amount;
                $total_fee += $order->total_fee;
            }
            //$total = $total_fee - $total_cod;
            $customer_bill = new PartnerBill();
            $customer_bill->order_ids = $order_ids;
            $customer_bill->created_time = time();
            $customer_bill->total_cod = $total_cod;
            $customer_bill->total_fee = $total_fee;
            $customer_bill->partner_bill_id = $partner_bill_id;
            $customer_bill->save();
            OrderDelivery::where('partner_status_payment', OrderDeliveryUtility::PARTNER_STATUS_PAYMENT_NEW)->whereIn('id', $order_ids)->update(['partner_bill_id' => $partner_bill_id, 'partner_status_payment' => OrderDeliveryUtility::PARTNER_STATUS_PAYMENT_SUCCESS]);
        });
        flash(translate('Bill created success'))->success();
        return redirect(route('partner_bill.index')) ;
    }

    public function cancel($id){
        $partner_bill = PartnerBill::where('id', $id)->first();
        if(!$partner_bill){
            return response([
                'result' => false,
                'message' => translate('Bill not found')
            ]);
        }
        \DB::transaction(function () use ($partner_bill){
            $partner_bill_id = $partner_bill->partner_bill_id;
            OrderDelivery::where('partner_status_payment', OrderDeliveryUtility::PARTNER_STATUS_PAYMENT_SUCCESS)->where('partner_bill_id', $partner_bill_id)->update(['partner_bill_id' => null, 'partner_status_payment' => OrderDeliveryUtility::PARTNER_STATUS_PAYMENT_NEW]);
            $partner_bill->delete();
        });
        return response([
            'result' => true,
            'message' => translate('Bill update payment success')
        ]);
    }







}
