<?php

namespace App\Http\Controllers\Hook;

use App\Http\Controllers\Controller;
use App\Models\CustomerPackage;
use App\Models\NoticeUser;
use App\Models\OrderDelivery;
use App\Models\OrderDeliveryLog;
use App\Models\User;
use App\Services\Extend\TelegramService;
use App\Utility\OrderDeliveryUtility;
use Illuminate\Http\Request;
use Monolog\Handler\TelegramBotHandler;

class OrderDeliveryController extends Controller
{
    public function __construct()
    {
    }

    public function bestExpress(Request $request)
    {
        TelegramService::sendMessage(json_encode($request->all()));
        $update_aff = 0;
        $order_id = explode('-', $request->merchant_order_code)[0];
        $data = [
            'order_delivery_id' => $order_id,
            'status_code' => $request->status,
            'status_content' => $request->status_content,
            'status_description' => OrderDeliveryUtility::$aryStatus[$request->status],
            'response' => json_encode($request->all()),
            'created_time' => (int)($request->modified_at) / 1000
        ];
        $order = OrderDelivery::find($order_id);
        switch ($request->status) {
            case "202":
                $update_aff = 1;
                $order->status = OrderDeliveryUtility::STATUS_COLLECTED;
                break;
            case "203":
                $order->status = OrderDeliveryUtility::STATUS_COLLECT_FAIL;
                break;
            case "301":
                $update_aff = 1;
                $order->status = OrderDeliveryUtility::STATUS_COLLECTED;
                break;
            case "302":
            case "303":
            case "304":
            case "309":
                $update_aff = 1;
                $order->status = OrderDeliveryUtility::STATUS_ON_WAY;
                break;
            case "601":
                $update_aff = 1;
                $order->status = OrderDeliveryUtility::STATUS_DELIVERY;
                break;
            case '666':
                $update_aff = 1;
                $order->status = OrderDeliveryUtility::STATUS_DELIVERED;
                $order->status_payment = OrderDeliveryUtility::STATUS_PAYMENT_PENDING;
                break;
            case '605':
            case '701':
            case '702':
            case '703':
            case '704':
            case '705':
                $update_aff = 1;
                $order->status = OrderDeliveryUtility::STATUS_RETURN;
                break;
            case '708':
                $update_aff = 1;
                $order->status = OrderDeliveryUtility::STATUS_RETURNED;
                $order->status_payment = OrderDeliveryUtility::STATUS_PAYMENT_PENDING;
                break;
            case '777':
            case '1000':
                $order->status = OrderDeliveryUtility::STATUS_LOST;
                break;
        }

        $customer_insurance_fee = 0;
        $product_price = isset($request->product_price) ? (int)$request->product_price : $order->product_price;
        if($product_price > OrderDeliveryUtility::MIN_FEE){
            $customer_insurance_fee = (int)round(0.005 * $product_price, 0);
        }
        $customer_cod_fee = 0;
        $collect_amount = isset($request->cod) ? $request->cod : $order->collect_amount;
        if ($collect_amount > OrderDeliveryUtility::MIN_FEE) {
            $customer_cod_fee = (int)round(0.005 * $collect_amount, 0);
        }
        $weight = isset($request->weight) ? (int)$request->weight : $order->weight;
        $width = isset($request->width) ? (int)$request->width : $order->width;
        $height = isset($request->height) ? (int)$request->height : $order->height;
        $length = isset($request->length) ? (int)$request->length : $order->length;
        $fee_plus = $this->feePlus($weight, $width, $height, $length);
        $user = User::find($order->user_id);
        $package_id = $user->customer_package_id;
        $package = CustomerPackage::find($package_id);
        $customer_delivery_fee = $fee_plus + $package->fee;
        $customer_total_fee = $customer_delivery_fee + $customer_insurance_fee + $customer_cod_fee;
        $customer_reason_fee = 0;
        if($customer_delivery_fee != $order->customer_delivery_fee){
            $customer_reason_fee = OrderDeliveryUtility::REASON_BEST_DELIVERY;
        }
        if($customer_insurance_fee != $order->customer_insurance_fee){
            $customer_reason_fee = OrderDeliveryUtility::REASON_BEST_INSURANCE;
        }
        if($customer_cod_fee != $order->customer_cod_fee){
            $customer_reason_fee = OrderDeliveryUtility::REASON_BEST_COD;
        }
        $order->customer_total_fee = $customer_total_fee;
        $order->customer_delivery_fee = $customer_delivery_fee;
        $order->customer_insurance_fee = $customer_insurance_fee;
        $order->customer_cod_fee = $customer_cod_fee;
        $order->customer_reason_fee = $customer_reason_fee;
        $order->delivery_fee = isset($request->freight_fee) ? (int)$request->freight_fee : $order->delivery_fee;
        $order->insurance_fee = isset($request->insurance_fee) ? (int)$request->insurance_fee : $order->insurance_fee;
        $order->cod_fee = isset($request->cod_fee) ? (int)$request->cod_fee : $order->cod_fee;
        $order->return_fee = isset($request->return_fee) ? (int)$request->return_fee : $order->return_fee;
        $order->total_fee = isset($request->total_fee) ? (int)$request->total_fee : $order->total_fee;
        $order->collect_amount = isset($request->cod) ? $request->cod : $order->collect_amount;
        $order->product_price = isset($request->product_price) ? $request->product_price : $order->product_price;
        $order->weight = isset($request->weight) ? $request->weight : $order->weight;
        $order->length = isset($request->length) ? $request->length : $order->length;
        $order->width = isset($request->width) ? $request->width : $order->width;
        $order->height = isset($request->height) ? $request->height : $order->height;
        if($order->affiliate_payment == 0 && $update_aff == 1){
            $order->affiliate_payment = 1;
        }else{
            $update_aff = 0;
        }
        $order->save();
        $order_log = new OrderDeliveryLog();
        foreach ($data as $key => $value) {
            $order_log->$key = $value;
        }
        $order_log->save();
        if ($update_aff == 1) {
            User::where('id', $order->kol_id)->increment('balance', $order->kol_value);
            User::where('id', $order->employee_id)->increment('balance', $order->employee_value);
            NoticeUser::updateOrCreate(
                ['user_id' => $order->kol_id],
                ['status' => 0]
            );
            NoticeUser::updateOrCreate(
                ['user_id' => $order->employee_id],
                ['status' => 0]
            );
        }
        return response(['Result' => 1, 'Message' => ""]);
    }

    public function feePlus($weight, $width, $height, $length){
        $weight_2 = (int)(((int)($width/10) * (int)($height/10) * (int)($length/10)) / 6);
        $weight = ($weight >= $weight_2) ? $weight : $weight_2;
        $fee_plus = 0;
        if($weight > 5000){
            $wf = $weight - 5000;
            $fee_plus = ceil($wf/500)*4000;
        }
        return $fee_plus;
    }
}
