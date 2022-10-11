<?php

namespace App\Http\Controllers;

use App\Models\AffiliatePayment;
use App\Models\WarrantyBill;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{

    public function configs(){

        return view('backend.affiliate.config');
    }
    public function employee(Request $request){
        $sort_search = null;
        $users = User::where('user_type', 'employee')->orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $users->where(function ($q) use ($sort_search){
                $q->where('name', 'like', '%'.$sort_search.'%')->orWhere('phone', 'like', '%'.$sort_search.'%');
            });
        }
        if ((isset($request->banned) ? $request->banned : -1) >= 0) {
            $users = $users->where('banned', $request->banned);
        }
        $users = $users->paginate(15);
        return view('backend.affiliate.employee.index', compact('users', 'sort_search'));
    }

    public function employee_create()
    {
        return view('backend.affiliate.employee.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function employee_store(Request $request)
    {
        if(User::where('phone', $request->phone)->first() == null){
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->referral_code = $request->phone;//Str::random(10);
            $user->user_type = "employee";
            $user->password = Hash::make($request->password);
            $user->save();
            flash(translate('Tạo tài khoản thành công'))->success();
            return redirect()->route('affiliate.employee.index');
        }
        flash(translate('Tài khoản đã tồn tại'))->error();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function employee_edit($id)
    {
        $user = User::findOrFail(decrypt($id));
        return view('backend.affiliate.employee.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function employee_update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if(strlen($request->password) > 0){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        flash(translate('Cập nhật thông tin nhân viên thành công'))->success();
        return redirect()->route('affiliate.employee.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function employee_destroy($id)
    {
        User::destroy($id);
        flash(translate('Xóa tài khoản thành công'))->success();
        return redirect()->route('affiliate.employee.index');
    }

    public function kol(Request $request){
        $sort_search = null;
        $employee = User::where('user_type', 'employee')->pluck('name', 'id');
        $users = User::where('user_type', 'kol')->orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $users->where(function ($q) use ($sort_search){
                $q->where('name', 'like', '%'.$sort_search.'%')->orWhere('phone', 'like', '%'.$sort_search.'%');
            });
        }
        if ((isset($request->banned) ? $request->banned : -1) >= 0) {
            $users = $users->where('banned', $request->banned);
        }
        if(!empty($request->referred_by)){
            $users = $users->where('referred_by', $request->referred_by);
        }
        $users = $users->paginate(15);
        return view('backend.affiliate.kol.index', compact('users', 'sort_search', 'employee'));
    }

    public function kol_create()
    {
        $employee = User::where('user_type', 'employee')->pluck('name', 'id');
        return view('backend.affiliate.kol.create', compact('employee'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function kol_store(Request $request)
    {
        if(User::where('phone', $request->phone)->first() == null){
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->referred_by = $request->referred_by;
            $user->referral_code = $request->phone;//Str::random(10);
            $user->user_type = "kol";
            $user->password = Hash::make($request->password);
            $user->save();
            flash(translate('Tạo tài khoản thành công'))->success();
            return redirect()->route('affiliate.kol.index');
        }
        flash(translate('Tài khoản đã tồn tại'))->error();
        return back();

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kol_edit($id)
    {
        $employee = User::where('user_type', 'employee')->pluck('name', 'id');
        $user = User::findOrFail(decrypt($id));
        return view('backend.affiliate.kol.edit', compact('user', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kol_update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->referred_by = $request->referred_by;
        if(strlen($request->password) > 0){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        flash(translate('Cập nhật thông tin nhân viên thành công'))->success();
        return redirect()->route('affiliate.kol.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kol_destroy($id)
    {
        User::destroy($id);
        flash(translate('Xóa tài khoản thành công'))->success();
        return redirect()->route('affiliate.kol.index');
    }

    public function requestPayment(){

        $payments = AffiliatePayment::where('status', 1);
        /*if (!empty($request->start_time)) {
            $payment = $payment->where('created_time', '>=', $request->start_time);
        }
        if (!empty($request->end_time)) {
            $payment = $payment->where('created_time', '<', $request->end_time);
        }
        if (!empty($request->status)) {
            $payment = $payment->where('status', $request->status);
        }*/
        $payments->with(['user.customer_bank']);

        $payments = $payments->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
        return view('backend.affiliate.request_payment', compact('payments'));
    }

    public function historyPayment(Request $request){
        $payments = AffiliatePayment::whereIn('status', [-1,2]);
        if ($request->date != null) {
            $payments = $payments->where('created_time', '>=', strtotime(explode(" to ", $request->date)[0]))->where('created_time', '<=', strtotime(explode(" to ", $request->date)[1]) + 86399);
        }
        if(!empty($request->employee_id)){
            $payments = $payments->where('user_id', $request->employee_id);
        }
        if(!empty($request->kol_id)){
            $payments = $payments->where('user_id', $request->kol_id);
        }
        $sort_search = null;
        if ($request->search != '') {
            /*$sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');*/
            $sort_search = $request->search;
            $user_ids = User::where('name', 'like', '%' . $sort_search . '%')->orWhere('phone', 'like', '%' . $sort_search . '%')->pluck('id');
            /*$payments = $payments->where(function ($q) use ($sort_search) {
                $q->where('name', $sort_search)->orWhere('partner_code', $sort_search)->orWhere('source_phone', $sort_search)->orWhere('dest_phone', $sort_search);
            });*/
            $payments = $payments->whereIn('user_id', $user_ids);
        }
        $payments->with(['user.customer_bank']);
        $payments = $payments->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
        return view('backend.affiliate.history_payment', compact('payments', 'sort_search'));
    }

    public function updatePayment($id){
        $payment = AffiliatePayment::where('id', $id)->where('status', 1)->first();
        if(!$payment){
            return response([
                'result' => false,
                'message' => 'Không tìm thấy yêu cầu cần thanh toán'
            ]);
        }

        $payment->status = 2;
        $payment->payment_time = time();
        $payment->payment_user_id = auth()->id();
        $payment->save();
        return response([
            'result' => true,
            'message' => 'Cập nhật thanh toán thành công'
        ]);

    }
    public function cancelPayment($id, Request $request){
        $payment = AffiliatePayment::where('id', $id)->where('status', 1)->first();
        if(!$payment){
            return response([
                'result' => false,
                'message' => 'Không tìm thấy yêu cầu cần thanh toán'
            ]);
        }

        /*$payment->payment_user_id = auth()->id();*/
        //$payment->save();
        DB::transaction(function () use ($payment, $request) {
            $user = User::find($payment->user_id);
            $user->balance = $user->balance + $payment->value;
            $user->save();
            $payment->status = -1;
            $payment->reason = $request->reason ?? '';
            $payment->save();
        });
        return response([
            'result' => true,
            'message' => 'Hủy thanh toán thành công'
        ]);
    }





}
