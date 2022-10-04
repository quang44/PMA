<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Imports\OrderStatus;
use App\Models\NoticeUser;
use App\Models\OrderDelivery;
use App\Models\OrderDeliveryLog;
use App\Models\User;
use App\Services\Extend\TelegramService;
use App\Utility\NotificationUtility;
use App\Utility\OrderDeliveryUtility;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CronJobsController extends Controller
{
    public function noticeNewOrder(){
        $notices = NoticeUser::where('status', 0)->get();
        foreach ($notices as $notice){
            $user = User::find($notice->user_id);
            if(!empty($user->device_token)){
                $request = null;
                $request->device_token = $user->device_token;
                $request->title = "Đơn hàng mới !";
                $request->text = "Bạn được nhận thưởng đơn hàng mới";

                $request->type = "affiliate";
                $request->id = $user->id;
                $request->user_id = $user->id;
                NotificationUtility::sendFirebaseNotification($request);
                $notice->status = 1;
                $user->save();
            }
        }
    }

    public function deleteUser(){
        $start = date('Y-m-d 00:00:00', strtotime('-15 day'));
        $end = date('Y-m-d 23:59:59', strtotime('-15 day'));
        $users = User::where('type', 'customer')->where('created_at', '>=' , $start)->where('created_at', '<=' , $end)->get();
        foreach ($users as $user){
            $count = OrderDelivery::where('user_id', $user->id)->count();
            if(empty($count)){
                $user->delete();
            }
        }
    }

    public function notificationOrder(){
        $order_ids = OrderDelivery::where('status', OrderDeliveryUtility::STATUS_SUCCESS)->pluck('id')->toArray();
        if($order_ids){
            $text = '
            <b>[Nguồn] : </b><code>GomDon</code>
            <b>[Tiêu đề] : </b><code>Có '. count($order_ids).' đơn hàng đang chờ lấy hàng</code>
            <b>[Danh sách đơn] : </b><a href="' . route('order_delivery.index', ['status_delivery' => OrderDeliveryUtility::STATUS_SUCCESS]) . '">'. implode(',', $order_ids) . '</a>';
            TelegramService::sendMessageDhGomdon($text);
        }
    }

    public function webhookTelegram(Request $request){
        $message = $request->message;
        if(!empty($message['text'])){
            $ary_text = explode(' ', $message['text']);
            switch ($ary_text[0]){
                case '/donhang':
                    $id = $ary_text[1] ?? 0;
                    $order = OrderDelivery::find($id);
                    if($order){
                        $status = OrderDeliveryUtility::$aryStatusDelivery[$order->status] ?? "--";
                        $text = '
                            <b>[ID] : </b><code>'. $order->id .'</code>
                            <b>[BEST_ID] : </b><code>'. $order->partner_code .'</code>
                            <b>[Khách hàng] : </b><code>'. $order->user->name .'</code>
                            <b>[Số điện thoại] : </b><code>'. $order->user->phone .'</code>
                            <b>[Thời gian tạo đơn] : </b><code>'. date('d-m-Y H:i:s', $order->created_time) .'</code>
                            <b>[Tình trạng giao hàng] : </b><code>'. $status .'</code>
                            <b>[Thu hộ] : </b><code>'. single_price($order->collect_amount) .'</code>
                            <b>[Phí vận chuyển best] : </b><code>'. single_price($order->total_fee) .'</code>
                            <b>[Phí vận chuyển gomdon] : </b><code>'. single_price($order->customer_total_fee) .'</code>';
                        TelegramService::sendMessageDhGomdon($text);
                    }else{
                        TelegramService::sendMessageDhGomdon('Không tìm thấy thông tin đơn hàng');
                    }
                    break;
                default:
                    //TelegramService::sendMessageDhGomdon('Không tìm thấy thông tin đơn hàng');
            }
        }
    }

    public function convertStatus(){
        $file = Excel::toArray([], storage_path('/gomdon1664690691.xlsx'));
        $array = $file[0];
        $headings = array_shift($array);
        array_walk(
            $array,
            function (&$row) use ($headings) {
                $row = array_combine($headings, $row);
            }
        );
        $i = 0;
        foreach ($array as $key => $value){
            $id = $order_id = explode('-', $value['Mã đối tác'])[0];
            if(strlen($id) == 7){
                $log = OrderDeliveryLog::where('order_delivery_id', $id)->where('status_code', 666)->first();
                if(!$log){
                    $i++;
                    $temp = array(
                        'status' => '666',
                        'weight' => $value['Trọng lượng'],
                        'length' => $value['Dài'],
                        'width' => $value['Rộng'],
                        'height' => $value['Cao'],
                        'cod' => $value['COD'],
                        'merchant_order_code' => $value['Mã đối tác'],
                        'code_best' => $value['Mã đơn hàng'],
                        'modified_at' => strtotime(str_replace('/', '-', $value['Ngày thành công'])) * 1000//strtotime('d/m/Y H:i', $value['Ngày thành công']) * 1000,
                        /*'product_price' => 0,
                        'freight_fee' => 24000,
                        'cod_fee' => 0,
                        'insurance_fee' => 0,
                        'vat_fee' => 0,
                        'total_fee' => 24000,*/
                    );
                    print_r($temp);echo '<pre>';
                    /*$client = new Client();
                    $option['verify'] = false;
                    $option['form_params'] = $temp;
                    $option['http_errors'] = false;
                    $client->request("POST", 'https://admin.gomdon.com.vn/hook/order/best-express', $option);*/
                }

            }
        }
        dd($i);
    }

}
