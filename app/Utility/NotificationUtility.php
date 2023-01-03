<?php

namespace App\Utility;

use App\Mail\InvoiceEmailManager;
use App\Models\User;
use App\Models\SmsTemplate;
use App\Http\Controllers\OTPVerificationController;
use Illuminate\Support\Carbon;
use Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderNotification;
use App\Models\FirebaseNotification;
use App\Models\Notification as NotificationCustomer;
class NotificationUtility
{
    public static function sendOrderPlacedNotification($order, $request = null)
    {
        //sends email to customer with the invoice pdf attached
        $array['view'] = 'emails.invoice';
        $array['subject'] = translate('A new order has been placed') . ' - ' . $order->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['order'] = $order;
        try {
            Mail::to($order->user->email)->queue(new InvoiceEmailManager($array));
            Mail::to($order->orderDetails->first()->product->user->email)->queue(new InvoiceEmailManager($array));
        } catch (\Exception $e) {

        }

        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'order_placement')->first()->status == 1) {
            try {
                $otpController = new OTPVerificationController;
                $otpController->send_order_code($order);
            } catch (\Exception $e) {

            }
        }

        //sends Notifications to user
        self::sendNotification($order, 'placed');
        if ($request !=null && get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order placed !";
            $request->text = "An order {$order->code} has been placed";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            self::sendFirebaseNotification($request);
        }
    }

    public static function sendNotification($order, $order_status)
    {
        if ($order->seller_id == \App\Models\User::where('user_type', 'admin')->first()->id) {
            $users = User::findMany([$order->user->id, $order->seller_id]);
        } else {
            $users = User::findMany([$order->user->id, $order->seller_id, \App\Models\User::where('user_type', 'admin')->first()->id]);
        }

        $order_notification = array();
        $order_notification['order_id'] = $order->id;
        $order_notification['order_code'] = $order->code;
        $order_notification['user_id'] = $order->user_id;
        $order_notification['seller_id'] = $order->seller_id;
        $order_notification['status'] = $order_status;

        Notification::send($users, new OrderNotification($order_notification));
    }

    public static function sendFirebaseNotification($req)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $db = null;
        $type = null;
//        $firebase_id=$req->firebase_id??null;

        if ( isset($req->data)) {
            $db = json_decode(json_encode($req->data, false));
        }

        $type= checkType($req->type);

        $data = [
            "title"=> $req->title,
            'click_action'=>'FLUTTER_NOTIFICATION_CLICK',
            'type' => $type,
            'item_id' => $db == null ? null : $db->id,
            'content' => $req->text,
            'timestamp' => strtotime(now()),
            "amount_first"=>$req->amountFirst??null,
            "amount_later"=> $req->amountFirst??null,
        ];

        $fields = array
        (
            'to' => isset($req->device_token)?$req->device_token:'/topics/'.$req->type,
            'notification' => [
                'title' => $req->title,
                'body' => $req->text,
            ],
            'data' => $data

        );
        //$fields = json_encode($arrayToSend);
        $key = "AAAARkqBIWQ:APA91bGYr7ar4Il45zlTeqZZL_6ErBcAokHuEhFZrsLMVz5oR2YpOeC80DAxkhHyg7yNu78cd4ms9x3DLMMfyxQUvvLlxp3Nt1GkWYErtqwLRZBjuynKKAIimTQc6C2AjBBnPyeH8_Si";

        $headers = array(
            'Authorization: key=' . $key,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    static function  SendNotifications($data){
        $notification=new NotificationCustomer();
       $notification->newQuery()->create($data);
    }
}
