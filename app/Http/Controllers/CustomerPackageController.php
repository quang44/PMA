<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerPackageRequest;
use App\Utility\PayfastUtility;
use Illuminate\Http\Request;
use App\Models\CustomerPackage;
use App\Models\CustomerPackagePayment;
use Auth;
use Session;
use App\Models\User;
use App\Http\Controllers\PublicSslCommerzPaymentController;
use App\Http\Controllers\InstamojoController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\VoguePayController;

class CustomerPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer_packages = CustomerPackage::all();
        return view('backend.customer.customer_packages.index', compact('customer_packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.customer.customer_packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerPackageRequest $request)
    {
        $group_default = CustomerPackage::where('default', 1)->first();
        $customer_package = new CustomerPackage;
        if($request->default && $request->default == 'on'){
            $group_default ->default = 0;
            $group_default->save();
            $customer_package->default = 1;
        }
        $customer_package->name = $request->name;
        $customer_package->avatar = $request->avatar;
        $customer_package->bonus = $request->bonus;
        $customer_package->description = $request->description;
        $customer_package->withdraw = $request->withdraw;
        $customer_package->point = $request->point;
        $customer_package->save();

        flash(translate('Nhóm người dùng đã được tạo thành công'))->success();
        return redirect()->route('customer_packages.index');
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
    public function edit(Request $request, $id)
    {
        $customer_package = CustomerPackage::findOrFail($id);
        return view('backend.customer.customer_packages.edit', compact('customer_package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerPackageRequest $request, $id)
    {
        $customer_package = CustomerPackage::findOrFail($id);
        $customer_package->name = $request->name;
        $customer_package->avatar = $request->avatar;
        $customer_package->bonus = $request->bonus;
        $customer_package->description = $request->description;
        $customer_package->withdraw = $request->withdraw;
        $customer_package->point = $request->point;
        $customer_package->save();

        flash(translate('Nhóm người dùng đã được cập nhật thành công'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer_package = CustomerPackage::findOrFail($id);
        foreach ($customer_package->customer_package_translations as $key => $customer_package_translation) {
            $customer_package_translation->delete();
        }
        CustomerPackage::destroy($id);

        flash(translate('Nhóm người đã được xóa thành công'))->success();
        return redirect()->route('customer_packages.index');
    }

    public function purchase_package(Request $request)
    {
        $data['customer_package_id'] = $request->customer_package_id;
        $data['payment_method'] = $request->payment_option;

        $request->session()->put('payment_type', 'customer_package_payment');
        $request->session()->put('payment_data', $data);

        $customer_package = CustomerPackage::findOrFail(Session::get('payment_data')['customer_package_id']);

        if ($customer_package->amount == 0) {
            $user = User::findOrFail(Auth::user()->id);
            if ($user->customer_package_id != $customer_package->id) {
                return $this->purchase_payment_done(Session::get('payment_data'), null);
            } else {
                flash(translate('You can not purchase this package anymore.'))->warning();
                return back();
            }
        }

        $decorator = __NAMESPACE__ . '\\Payment\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $request->payment_option))) . "Controller";
        if (class_exists($decorator)) {
            return (new $decorator)->pay($request);
        }
    }

    public function purchase_payment_done($payment_data, $payment)
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->customer_package_id = $payment_data['customer_package_id'];
        $customer_package = CustomerPackage::findOrFail($payment_data['customer_package_id']);
        $user->remaining_uploads += $customer_package->product_upload;
        $user->save();

        flash(translate('Package purchasing successful'))->success();
        return redirect()->route('dashboard');
    }

    public function purchase_package_offline(Request $request)
    {
        $customer_package = new CustomerPackagePayment;
        $customer_package->user_id = Auth::user()->id;
        $customer_package->customer_package_id = $request->package_id;
        $customer_package->payment_method = $request->payment_option;
        $customer_package->payment_details = $request->trx_id;
        $customer_package->approval = 0;
        $customer_package->offline_payment = 1;
        $customer_package->reciept = ($request->photo == null) ? '' : $request->photo;
        $customer_package->save();
        flash(translate('Offline payment has been done. Please wait for response.'))->success();
        return redirect()->route('customer_products.index');
    }

    public function setup_hidden(Request $request)
    {
        $customer_package = CustomerPackage::findOrFail($request->id);
        if ($customer_package->default != 0) {
            flash(translate('Nhóm người dùng mặc định không được ở trạng thái ẩn !'))->error();
            return 0;
        }
        $customer_package->status = (int)$request->status;
        if ($customer_package->save()) {
            flash(translate('Trạng thái ẩn nhóm người dùng được thay đổi !'))->success();
            return 1;
        }
        return 0;
    }

    public function setup_default(Request $request)
    {

        $customer_package = CustomerPackage::findOrFail($request->id);
        if ($customer_package->status != 0) {
            flash(translate('Nhóm người dùng mặc định không được ở trạng thái ẩn !'))->error();
            return 0;
        } elseif ($customer_package->default != 0) {
            flash(translate('Nhóm người dùng đang ở chế độ mặc định !'))->error();
            return 0;
        } else {
            if ($request->status == 1) {
                CustomerPackage::where('id', '>', 0)->update(['default' => 0]);
            }
        }
        $customer_package->default = (int)$request->status;
        if ($customer_package->save()) {
            flash(translate('Nhóm người dùng đã được cài làm mặc định !'))->success();
            return 1;
        }
        return 0;
    }
}
