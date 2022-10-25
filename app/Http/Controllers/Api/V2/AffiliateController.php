<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\AffiliatePaymentCollection;
use App\Http\Resources\V2\OrderAffiliateCollection;
use App\Http\Resources\V2\OrderDeliveryCollection;
use App\Http\Resources\V2\UserCollection;
use App\Models\AffiliatePayment;
use App\Models\CommonConfig;
use App\Models\CustomerBank;
use App\Models\CustomerPackage;
use App\Models\NoticeUser;
use App\Models\OrderDelivery;
use App\Models\User;
use App\Utility\NotificationUtility;
use App\Utility\OrderDeliveryUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateController extends Controller
{
    public function listSub(Request $request)
    {
        $user = auth()->user();
        $user_type = '';
        if ($user->user_type == 'employee') {
            if (!empty($request->kol_id)) {
                $user_type = 'customer';
                $users = User::where('user_type', $user_type)->where('referred_by', $request->kol_id);
            } else {
                $user_type = 'kol';
                $users = User::where('user_type', $user_type)->where('referred_by', $user->id);
            }
        } else {
            $user_type = 'customer';
            $users = User::where('user_type', $user_type)->where('referred_by', $user->id);
        }
        $keyword = $request->search;
        if (!empty($keyword)) {
            $users = $users->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')->orWhere('phone', 'like', '%' . $keyword . '%');
            });
        }
        if ((int)$request->start_time > 0) {
            $users = $users->where('created_at', '>=', date('Y-m-d H:i:s', $request->start_time));
        }
        if ((int)$request->end_time > 0) {
            $users = $users->where('created_at', '<=', date('Y-m-d H:i:s', $request->end_time));
        }
        $users = $users->orderBy('created_at', 'desc')->get();
        $ids = $users->pluck('id');
        if ($user_type == 'kol') {
            $user_shop = User::whereIn('referred_by', $ids)->select('referred_by', DB::raw('count(*) as count'))->groupBy('referred_by')->get()->pluck('count', 'referred_by')->toArray();
            $order_count = OrderDelivery::whereIn('kol_id', $ids)->where('affiliate_payment', 1)->select('kol_id', DB::raw('count(*) as count'))->get()->pluck('count', 'kol_id')->toArray();
        }
        if ($user_type == 'customer') {
            $order_count = OrderDelivery::whereIn('user_id', $ids)->where('affiliate_payment', 1)->select('user_id', DB::raw('count(*) as count'))->groupBy('user_id')->get()->pluck('count', 'user_id')->toArray();
        }
        if ($users) {
            foreach ($users as $key => $user) {
                $user['count_shop'] = isset($user_shop[$user->id]) ? $user_shop[$user->id] : 0;
                $user['count_order'] = isset($order_count[$user->id]) ? $order_count[$user->id] : 0;
                $users[$key] = $user;
            }
            if ($user_type == 'kol') {
                $users = $users->sortByDesc('count_shop')->values();
            }
            if ($user_type == 'customer') {
                $users = $users->sortByDesc('count_order')->values();
            }
        }
        return new UserCollection($users);
        /*$user = auth()->user();
        if ($user->user_type == 'employee') {
            if (!empty($request->kol_id)) {
                $users = User::where('user_type', 'customer')->where('referred_by', $request->kol_id);
            } else {
                $users = User::where('user_type', 'kol')->where('referred_by', $user->id);
            }
        } else {
            $users = User::where('user_type', 'customer')->where('referred_by', $user->id);
        }

        $users = $users->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
        return new UserCollection($users);*/
    }

    public function static(Request $request)
    {

        $user = auth()->user();
        $user_type = $user->user_type == 'employee' ? 'kol' : 'customer';
        $referred_by = $user->id;
        if ($user_type == 'kol' && !empty($request->kol_id)) {
            $user_type = 'customer';
            $referred_by = $request->kol_id;
        }
        $all = User::where('user_type', $user_type)->where('referred_by', $referred_by)->count();
        $today = User::where('user_type', $user_type)->where('referred_by', $referred_by)->where('created_at', '>=', date('Y-m-d 00:00:00', time()))->where('created_at', '<=', date('Y-m-d 23:59:59', time()))->count();
        $this_week = User::where('user_type', $user_type)->where('referred_by', $referred_by)->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('monday this week')))->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime('sunday this week')))->count();
        $this_month = User::where('user_type', $user_type)->where('referred_by', $referred_by)->where('created_at', '>=', date('Y-m-01 00:00:00', time()))->where('created_at', '<=', date('Y-m-t 23:59:59', time()))->count();
        $has_order = 0;
        $no_order = 0;
        $has_shop = 0;
        $no_shop = 0;
        if ($user_type == 'kol') {
            $kol_ids = User::where('referred_by', $referred_by)->pluck('id');
            $has_shop = User::whereIn('referred_by', $kol_ids)->distinct('referred_by')->count();
            $no_shop = $all - $has_shop;
            $has_order = OrderDelivery::whereIn('kol_id', $kol_ids)->where('affiliate_payment', 1)->distinct('kol_id')->count();
            $no_order = $all - $has_order;
        }
        if ($user_type == 'customer') {
            $has_order = User::where('user_type', $user_type)->where('referred_by', $referred_by)->whereHas('orders', function ($query) {
                $query->where('affiliate_payment', 1);
            })->count();
            $no_order = $all - $has_order;
        }

        $data = [
            'all' => $all,
            'today' => $today,
            'this_week' => $this_week,
            'this_month' => $this_month,
            'has_order' => $has_order,
            'no_order' => $no_order,
            'has_shop' => $has_shop,
            'no_shop' => $no_shop,
        ];
        return response([
            'result' => true,
            'data' => $data
        ]);

    }

    public function order_static()
    {
        $user = auth()->user();
        $user_type = $user->user_type;
        if ($user_type == 'employee') {
            $all = OrderDelivery::where('employee_id', $user->id)->where('affiliate_payment', 1)->count();
            $today = OrderDelivery::where('employee_id', $user->id)->where('affiliate_payment', 1)->where('created_at', '>=', date('Y-m-d 00:00:00', time()))->where('created_at', '<=', date('Y-m-d 23:59:59', time()))->count();
            $this_week = OrderDelivery::where('employee_id', $user->id)->where('affiliate_payment', 1)->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('monday this week')))->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime('sunday this week')))->count();
            $this_month = OrderDelivery::where('employee_id', $user->id)->where('affiliate_payment', 1)->where('created_at', '>=', date('Y-m-01 00:00:00', time()))->where('created_at', '<=', date('Y-m-t 23:59:59', time()))->count();
        }
        if ($user_type == 'kol') {
            $all = OrderDelivery::where('kol_id', $user->id)->where('affiliate_payment', 1)->count();
            $today = OrderDelivery::where('kol_id', $user->id)->where('affiliate_payment', 1)->where('created_at', '>=', date('Y-m-d 00:00:00', time()))->where('created_at', '<=', date('Y-m-d 23:59:59', time()))->count();
            $this_week = OrderDelivery::where('kol_id', $user->id)->where('affiliate_payment', 1)->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('monday this week')))->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime('sunday this week')))->count();
            $this_month = OrderDelivery::where('kol_id', $user->id)->where('affiliate_payment', 1)->where('created_at', '>=', date('Y-m-01 00:00:00', time()))->where('created_at', '<=', date('Y-m-t 23:59:59', time()))->count();
        }
        $data = [
            'all' => $all ?? 0,
            'today' => $today ?? 0,
            'this_week' => $this_week ?? 0,
            'this_month' => $this_month ?? 0,
        ];
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function payment_static()
    {
        $id = auth()->id();

        /*if (!empty($request->status)) {
            $payment = $payment->where('status', $request->status);
        }*/
        $all = AffiliatePayment::where('user_id', $id)->count();
        $today = AffiliatePayment::where('user_id', $id)->where('created_at', '>=', date('Y-m-d 00:00:00', time()))->where('created_at', '<=', date('Y-m-d 23:59:59', time()))->count();
        $this_week = AffiliatePayment::where('user_id', $id)->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('monday this week')))->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime('sunday this week')))->count();
        $this_month = AffiliatePayment::where('user_id', $id)->where('created_at', '>=', date('Y-m-01 00:00:00', time()))->where('created_at', '<=', date('Y-m-t 23:59:59', time()))->count();
        $status_process = AffiliatePayment::where('user_id', $id)->where('status', 1)->count();
        $status_done = AffiliatePayment::where('user_id', $id)->where('status', 2)->count();
        $status_cancel = AffiliatePayment::where('user_id', $id)->where('status', -1)->count();
        $data = [
            'all' => $all,
            'today' => $today,
            'this_week' => $this_week,
            'this_month' => $this_month,
            'process' => $status_process,
            'done' => $status_done,
            'cancel' => $status_cancel
        ];
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function historyPayment(Request $request)
    {
        $id = auth()->id();
        $payment = AffiliatePayment::where('user_id', $id);
        if (!empty($request->start_time)) {
            $payment = $payment->where('created_time', '>=', $request->start_time);
        }
        if (!empty($request->end_time)) {
            $payment = $payment->where('created_time', '<', $request->end_time);
        }
        if (!empty($request->status)) {
            $payment = $payment->where('status', $request->status);
        }
        $payment = $payment->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
        return new AffiliatePaymentCollection($payment);
    }


    public function requestPayment(Request $request)
    {

        $user = User::with('customer_package')->find(auth()->user()->id);

        $value = (int)$request->value;
        $configPoint = CommonConfig::first();
        $point = $value / (int)$configPoint->exchange;

        if ($value < 10000) {
            return response([
                'result' => $user->customer_package,
                'message' =>'Số tiền cần thanh toán phải từ 10.000 vnđ trở lên'
            ]);
        }

        if (!is_int($point)) {
            return response([
                'result' => false,
                'message' => 'Số tiền không hợp lệ'
            ]);
        }

        if($value > $user->customer_package->withdraw){
            return response([
                'result' => false,
                'message' => "số tiền tối thiểu bạn có thẻ rút là ".number_format($user->customer_package->withdraw,0, ',', '.')." vnđ "
            ]);
        }

            $balance=available_balances($user->id);
//return response([
//    'data'=>$balance
//]);
        if ($point > $balance) {
            return response([
                'result' => false,
                'message' => 'Số tiền cần thanh toán nhiều hơn số dư tài khoản'
            ]);
        }
        $customer_bank = CustomerBank::where('user_id', $user->id)->first();
        if (!$customer_bank) {
            return response([
                'result' => false,
                'message' => 'Vui lòng cập nhật thông tin tài khoản ngân hàng trước'
            ]);
        }

        DB::transaction(function () use ($user, $value,$point, $customer_bank) {
            $user->balance = $user->balance - $point;
            $user->save();
            $request = new AffiliatePayment();
            $request->user_id = $user->id;
            $request->value = $point;
            $request->vat = (int)($point * 10 / 100);
            $request->amount = $request->value - $request->vat;
            $request->status = 1; // gửi yêu cầu
            $request->bank_name = $customer_bank->name;
            $request->bank_username = $customer_bank->username;
            $request->bank_number = $customer_bank->number;
            $request->created_time = time();
            $request->save();
        });
        return response([
            'result' => true,
        ]);
    }

    public function cancelPayment(Request $request)
    {
        $user = User::find(auth()->id());
        $payment = AffiliatePayment::where('id', $request->id)->where('user_id', $user->id)->first();
        if (!$payment) {
            return response([
                'result' => false,
                'message' => "Không tìm thấy thông tin cần thành toán"
            ]);
        }
        if ($payment->status != 1) {
            return response([
                'result' => false,
                'message' => "Không tìm thấy thông tin cần thành toán"
            ]);
        }

        DB::transaction(function () use ($user, $payment) {
            $pointConfig=CommonConfig::first();
            $point=$payment->value/(int)$pointConfig->exchange;
            $user->balance = $user->balance + $point;
            $user->save();
            $payment->status = -1; // huy tt
            if ($user->user_type == 'employee') {
                $payment->reason = 'Nhân viên tự hủy yêu cầu thanh toán';
            }
            if ($user->user_type == 'kol') {
                $payment->reason = 'Cộng tác viên tự hủy yêu cầu thanh toán';
            }
            $payment->save();
        });
        return response([
            'result' => true,
            'messages'=>'Gửi yêu cầu hủy rút tiền thành công'
        ]);

    }

    public function report()
    {
        $user = auth()->user();
        $user_type = $user->user_type;
        $count_order = $count_sub = 0;
        $count_sub = User::where('referred_by', $user->id)->where('banned', 0)->count();
        if ($user_type == 'employee') {
            $count_order = OrderDelivery::where('employee_id', $user->id)->where('status_payment', OrderDeliveryUtility::STATUS_PAYMENT_SUCCESS)->count();
        }
        if ($user_type == 'kol') {
            $count_order = OrderDelivery::where('kol_id', $user->id)->where('status_payment', OrderDeliveryUtility::STATUS_PAYMENT_SUCCESS)->count();
        }
        return response([
            'result' => true,
            'data' => [
                'count_order' => $count_order,
                'count_sub' => $count_sub
            ]
        ]);
    }

    public function orders(Request $request)
    {
        $user = auth()->user();
        $user_type = $user->user_type;
        if ($user_type != 'employee' && $user_type != 'kol') {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ]);
        }

        if ($user_type == 'employee') {
            $orders = OrderDelivery::where('employee_id', $user->id)/*->where('status_payment', OrderDeliveryUtility::STATUS_PAYMENT_SUCCESS)*/
            ;
        }
        if ($user_type == 'kol') {
            $orders = OrderDelivery::where('kol_id', $user->id)/*->where('status_payment', OrderDeliveryUtility::STATUS_PAYMENT_SUCCESS)*/
            ;
        }

        if (!empty($request->shop_id)) {
            $orders = $orders->where('user_id', (int)$request->shop_id);
        }
        if ((int)$request->start_time > 0) {
            $orders = $orders->where('created_time', '>=', (int)$request->start_time);
        }
        if ((int)$request->end_time > 0) {
            $orders = $orders->where('created_time', '<=', (int)$request->end_time);
        }
        $orders = $orders->where('affiliate_payment', 1);
        $orders->with('user');
        $orders = $orders->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
        return new OrderDeliveryCollection($orders);
    }

    public function order_detail(Request $request)
    {
        $user = auth()->user();
        $user_type = $user->user_type;
        if ($user_type != 'employee' && $user_type != 'kol') {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ]);
        }
        $order = null;
        if ($user_type == 'employee') {
            $order = OrderDelivery::where('employee_id', $user->id)->where('id', $request->id)->first();
        }
        if ($user_type == 'kol') {
            $order = OrderDelivery::where('kol_id', $user->id)->where('id', $request->id)->first();
        }
        if (!$order) {
            return response([
                'result' => false,
                'message' => trans('Không tìm thấy đơn hàng')
            ]);
        }
        return response([
            'result' => true,
            'data' => $order,
        ]);
    }
}
