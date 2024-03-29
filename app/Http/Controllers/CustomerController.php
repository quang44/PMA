<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Address;
use App\Models\AffiliatePayment;
use App\Models\CommonConfig;
use App\Models\CustomerPackage;
use App\Models\District;
use App\Models\OrderDelivery;
use App\Models\Province;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Ward;
use App\Utility\OrderDeliveryUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $data=[];

        $users = User::with('customer_bank', 'user_updated', 'addresses', 'user_agent')
            ->select(['id', 'name' , 'email' , 'address' , 'phone' , 'status', 'banned', 'belong','created_at','updated_at'])
            ->where('user_type', 'customer')
            ->whereNotNull('email_verified_at')
            ->orderByDesc('id');


//
        if (!empty($request->search)) {
            $sort_search = $request->search;
            $users = $users->where('name', 'like', '%' . $sort_search . '%')
                ->orWhere('phone', 'like', '%' . $sort_search . '%');
        }

        if (!empty($request->referred_by)) {
            $users = $users->where('referred_by', $request->referred_by);
        }
        if ((isset($request->banned) ? $request->banned : -1) >= 0) {
                    $users = $users->where('banned', $request->banned);
        }
//        else {
//            $users = $users->whereIn('banned', [0, 1]);
//        }

//        if ((isset($request->bank_updated) ? $request->bank_updated : -1) >= 0) {
//            $users = $users->where('bank_updated', $request->bank_updated);
//        }
//        if ($request->has_best_api > 0) {
//            if ($request->has_best_api == 1) {
//                $users = $users->whereNull('best_api_user');
//            } elseif ($request->has_best_api == 2) {
//                $users = $users->whereNotNull('best_api_user');
//            }
//        }





        $users = $users-> paginate(15);
        $packages = CustomerPackage::all();
        $kols = User::where('user_type', 'kol')->pluck('name', 'id');
        return view('backend.customer.customers.index', compact('users', 'sort_search', 'packages', 'kols'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $province=Province::all();
        $depots=User::query()->where('user_type','employee')
            ->where('banned',0) ->get();
        return view('backend.customer.customers.create', compact('province','depots'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(CustomerRequest $request)
    {
//        $response['status'] = 'Error';
        $package = CustomerPackage::where('default', 1)->first();
        $common_config = CommonConfig::first();
        $referred_by=null;
        $user =new User();
        $user->fill($request->all());
        $user->provider_id=$request->depot;
        $user->password=Hash::make($request->password);
        $user->customer_package_id=CustomerPackage::where('default',1)->first()->id;
        $user->email_verified_at=now();
        $user->belong=$request->depot;
        $user->save();

//        Address::create([
//            'user_id'=>$user->id,
//            'province_id'=>$request->province,
//            'district_id'=>$request->district,
//            'ward_id'=>$request->ward,
//            'phone'=>$request->phone,
//            'name'=>$request->name,
//        ]);
        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $amount = $referred_by != null ? $package->bonus + $common_config->for_activator : $package->bonus;
        $wallet->amount = config_base64_encode($amount);
        $wallet->payment_method = translate('Hệ thống');
        $wallet->save();
        flash('Tài khoản đã được tạo thành công')->success();
        return  redirect()->route('customers.index');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with(['customer_package','user_agent'])->findOrFail(decrypt($id));
        return view('backend.customer.customers.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user = User::with('user_agent','address_one')->findOrFail(decrypt($id));
//        $province=Province::all();
//        $districts=District::all();
//        $wards=Ward::all();
        $depots=User::where('user_type','employee')->where('banned',0)->get();

        return view('backend.customer.customers.edit', compact('user','depots'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string|unique:users,phone,' . $id,
        ], [
            'name.required' => 'Vui lòng nhập tên',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.unique' => 'Số điện thoại đã tồn tại',
        ]);

        $user = User::query()->findOrFail($id);

        $user->fill($request->all());

        if (!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }
        $user->updated_by = auth()->id();
        $user->belong = $request->depot;
        $user->save();
//        $address = Address::query()->where('user_id', $user->id)->first();
//        if($address){
//            $address->province_id = $request->province;
//            $address->district_id = $request->district;
//            $address->ward_id = $request->ward;
//            $address->phone = $request->phone;
//            $address->name = $request->name;
//            $address->save();
//        }


//        if ($con1 == 1 && $con2 == 1) {
//            if (!empty($user->device_token)) {
//                $req = new \stdClass();
//                $req->device_token = $user->device_token;
//                $req->title = "Kích hoạt tài khoản !";
//                $req->text = "Tài khoản của bạn đã được kích hoạt";
//
//                $req->type = "active_user";
//                $req->id = $user->id;
//                $req->best_api_user = $user->best_api_user;
//                NotificationUtility::sendFirebaseNotification($req);
//            }

//        }
        flash('Tài khoản đã được cập nhật thành công')->success();
        return redirect()->route('customers.index');
    }

    function updateToAgent( $id){
       $user=User::findOrFail(decrypt($id));
       $user->status=1;
       $user->save();
        flash(translate('Customer has been updated successfully'))->success();
        return redirect()->route('affiliate.employee.index');
    }

    function updateToDepot( $id){
        $user=User::findOrFail(decrypt($id));
        $user->status=1;
        $user->provinder_id=0;
        $user->save();
        flash(translate('Customer has been updated successfully'))->success();
        return redirect()->route('affiliate.depot.index');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        flash('Tài khoản đã được xóa thành công')->success();
        return redirect()->route('customers.index');
    }

    public function bulk_customer_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $customer_id) {
                $this->destroy($customer_id);
            }
        }

        return 1;
    }

    public function login($id)
    {
        $user = User::findOrFail(decrypt($id));

        auth()->login($user, true);

        return redirect()->route('dashboard');
    }

    public function ban($id)
    {
        $user = User::findOrFail(decrypt($id));

        if ($user->banned == 1) {
            $user->banned = 0;
            flash('Tài khoản đã được kích hoạt thành công')->success();
        } else {
            $user->banned = 1;
            flash('Tài khoản đã  được khóa thành công')->success();
        }

        $user->save();

        return back();
    }

    public function bank($id)
    {
        $user = User::findOrFail(decrypt($id));
        $user->bank_updated = 2;
        $user->save();
        flash(translate('Cập nhật tài khoản ngân hàng của shop bên Best thành công'))->success();
        return back();
    }

    public function customerPay(Request $request)
    {
        $sort_search = null;
        $user_ids = OrderDelivery::where('status_payment', OrderDeliveryUtility::STATUS_PAYMENT_PENDING)->pluck('user_id');
        $users = User::where('user_type', 'customer')->whereIn('id', $user_ids);
        if ($request->has('search')) {
            $sort_search = $request->search;
            $users->where(function ($q) use ($sort_search) {
                $q->where('name', 'like', '%' . $sort_search . '%')->orWhere('email', 'like', '%' . $sort_search . '%')->orWhere('phone', 'like', '%' . $sort_search . '%');
            });
        }
        $users = $users->paginate(15);
        $ids = $users->pluck('id')->toArray();
        $orders = OrderDelivery::where('status_payment', OrderDeliveryUtility::STATUS_PAYMENT_PENDING)->whereIn('user_id', $ids)->get();
        $aryTotal = [];
        if ($orders) {
            foreach ($orders as $order) {
                if (!isset($aryTotal[$order->user_id])) {
                    $aryTotal[$order->user_id] = 0;
                }
                $aryTotal[$order->user_id] += ($order->collect_amount - $order->total_fee);
            }
        }
        return view('backend.customer.customers.customer_pay', compact('users', 'sort_search', 'aryTotal'));
    }

    public function updatePackage(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->customer_package_id = $request->package_id;
        $user->save();
        return response([
            'result' => true
        ]);
    }

    function historyPayment(Request $request, $id){
        $payments = AffiliatePayment::where('user_id',decrypt($id))->whereIn('status', [-1, 2]);

        if ($request->date != null) {

            $payments = $payments->where('created_time', '>=', strtotime(explode(" to ", $request->date)[0]))->where('created_time', '<=', strtotime(explode(" to ", $request->date)[1]) + 86399);
        }
        $payments->with(['user.customer_bank']);
        $payments = $payments->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
        return view('backend.customer.customers.customerPayment', compact('payments'));
    }

}
