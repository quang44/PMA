<?php

namespace App\Http\Controllers;

use App\Models\CustomerPackage;
use App\Models\OrderDelivery;
use App\Services\Delivery\BestExpressService;
use App\Utility\NotificationUtility;
use App\Utility\OrderDeliveryUtility;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Order;

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
        $users = User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $users->where(function ($q) use ($sort_search) {
                $q->where('name', 'like', '%' . $sort_search . '%')->orWhere('phone', 'like', '%' . $sort_search . '%');
            });
        }
        if (!empty($request->referred_by)) {
            $users = $users->where('referred_by', $request->referred_by);
        }
        if ((isset($request->banned) ? $request->banned : -1) >= 0) {
            switch ($request->banned){
                case 1:
                    $users = $users->where('banned', $request->banned);
                    break;
                case 0:
                    $users = $users->where('banned', 0)->whereNotNull('best_api_user');
                    break;
                case 2:
                    $users = $users->where('banned', 0)->whereNull('best_api_user');
                    break;
            }

        }else{
            $users = $users->whereIn('banned', [0, 2]);
        }
        if ((isset($request->bank_updated) ? $request->bank_updated : -1) >= 0) {
            $users = $users->where('bank_updated', $request->bank_updated);
        }
        if ($request->has_best_api > 0) {
            if($request->has_best_api == 1){
                $users = $users->whereNull('best_api_user');
            }elseif ($request->has_best_api == 2){
                $users = $users->whereNotNull('best_api_user');
            }
        }
        $users->with('customer_bank', 'user_updated');
        $users = $users->paginate(15);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'phone' => 'required|unique:users',
        ]);

        $response['status'] = 'Error';

        $user = User::create($request->all());

        $customer = new Customer;

        $customer->user_id = $user->id;
        $customer->save();

        if (isset($user->id)) {
            $html = '';
            $html .= '<option value="">
                        ' . translate("Walk In Customer") . '
                    </option>';
            foreach (Customer::all() as $key => $customer) {
                if ($customer->user) {
                    $html .= '<option value="' . $customer->user->id . '" data-contact="' . $customer->user->email . '">
                                ' . $customer->user->name . '
                            </option>';
                }
            }

            $response['status'] = 'Success';
            $response['html'] = $html;
        }

        echo json_encode($response);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail(decrypt($id));
        $packages = CustomerPackage::all();
        //$kols = User::where('user_type', 'kol')->pluck('name', 'id');
        return view('backend.customer.customers.edit', compact('user', 'packages'));
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
            'email' => 'nullable|string|email',
            'name' => 'required|string',
            'phone' => 'required|string|unique:users,phone,'.$id,
            'customer_package_id' => 'required',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'email.email' => 'Email không đúng định dạng',
            'name.required' => 'Vui lòng nhập tên shop',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'customer_package_id.required' => 'Vui lòng chọn gói',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự',
            'password.confirmed' => 'Vui lòng xác nhận mật khẩu',
        ]);
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->customer_package_id = $request->customer_package_id;
        //$user->referred_by = $request->referred_by;
        $con1 = $con2 = 0;
        if(empty($user->best_api_user)){
            $con1 = 1;
        }
        if(!empty($request->best_api_user) && ($user->best_api_user != $request->best_api_user || $user->best_api_password != $request->best_api_password)){
            $best = new BestExpressService();
            $token = $best->checkLogin($request->best_api_user, $request->best_api_password);
            if(empty($token)){
                return back()->withErrors(['best_api_user' => 'Tài khoản best không đúng']);
            }
            $user->best_api_user = $request->best_api_user;
            $user->best_api_password = $request->best_api_password;
            $user->best_api_token = $token;
            $con2 = 1;
        }
        if(!empty($request->password)){
            $user->password = bcrypt($request->password);
        }
        $user->updated_by = auth()->id();
        $user->save();
        if($con1 == 1 && $con2 == 1){
            if(!empty($user->device_token)){
                $req = new \stdClass();
                $req->device_token = $user->device_token;
                $req->title = "Kích hoạt tài khoản !";
                $req->text = "Tài khoản của bạn đã được kích hoạt";

                $req->type = "active_user";
                $req->id = $user->id;
                $req->best_api_user = $user->best_api_user;
                NotificationUtility::sendFirebaseNotification($req);
            }

        }
        flash(translate('Cập nhật thông tin tài khoản thành công'))->success();
        return redirect()->route('customers.index');
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
        flash(translate('Customer has been deleted successfully'))->success();
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
            flash(translate('Customer UnBanned Successfully'))->success();
        } else {
            $user->banned = 1;
            flash(translate('Customer Banned Successfully'))->success();
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
}
