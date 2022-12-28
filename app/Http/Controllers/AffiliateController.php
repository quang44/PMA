<?php

    namespace App\Http\Controllers;

    use App\Models\Address;
    use App\Models\AffiliatePayment;
    use App\Models\CommonConfig;
    use App\Models\CustomerBank;
    use App\Models\District;
    use App\Models\Province;
    use App\Models\User;
    use App\Models\Wallet;
    use App\Utility\CustomerBillUtility;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;

    class AffiliateController extends Controller
    {

        public function configs()
        {

            return view('backend.affiliate.config');
        }

        public function employee(Request $request)
        {
            $sort_search = null;
            $users = User::where('user_type', 'employee')
                ->where('belong','>',0)
                ->with('addresses','user_agent')
                ->orderBy('updated_at', 'desc');
            if ($request->has('search')) {
                $sort_search = $request->search;
                $users->where(function ($q) use ($sort_search) {
                    $q->where('name', 'like', '%' . $sort_search . '%')->orWhere('phone', 'like', '%' . $sort_search . '%');
                });
            }
            if ((isset($request->banned) ? $request->banned : -1) >= 0) {
                $users = $users->where('banned', $request->banned);
            }
            $users = $users->paginate(15);
            $province=Province::all()->pluck('name','id');
            $district=District::all()->pluck('name','id');

            return view('backend.affiliate.employee.index', compact('users', 'sort_search','province','district'));
        }

        public function employee_create()
        {
            $provinces=Province::all();
            $employee =User::where('user_type','employee')->where('belong',0)->get();
            return view('backend.affiliate.employee.create',compact('provinces','employee'));
        }

        public function combination(){
            $provinces=Province::all();
            return view('backend.affiliate.employee.combinations',compact('provinces'));
        }

        function updateToAgent( $id){
            $user=User::findOrFail(decrypt($id));
            $user->status=2;
            $user->user_type='employee';
            $user->save();
            flash(translate('Customer has been updated successfully'))->success();
            sendFireBase($user,'Nâng cấp tài khoản','Tài khoản của bạn đã được nâng cập lên đại lý','upgrade_account');
            return redirect()->route('affiliate.employee.index');
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function employee_store(Request $request)
        {

            if (User::where('phone', $request->phone)->first() == null) {
                $user = new User;
                $user->fill($request->except('city','district'));
                $user->belong=$request->depot;
                $user->referral_code = $request->phone;//Str::random(10);
                $user->user_type = "employee";
//                $user->address = $request->address;
                $user->password = Hash::make($request->password);
                $user->save();
                if(in_array(0,$request->city)){
                    $address=Address::query()->where('user_id',$request->depot)->first();
                    Address::query()->create([
                        'user_id'=>$user->id,
                        'province_id'=>$address->province_id,
                        'district_id'=>$address->district_id,
                        'ward_id'=>$address->ward_id,
                    ]);
                }else{
                    foreach ($request->city as $key=>$city){
                        Address::query()->create([
                            'user_id'=>$user->id,
                            'province_id'=>$city,
                            'district_id'=>$request->district[$key],
                            'ward_id'=>$request->ward[$key],
                        ]);
                    }
                }


                flash(translate('Tạo tài khoản thành công'))->success();
                return redirect()->route('affiliate.employee.index');
            }
            flash(translate('Tài khoản đã tồn tại'))->error();
            return back();
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
        public function employee_edit($id)
        {
            $provinces=Province::all();
            $user = User::with('user_agent','addresses')->findOrFail(decrypt($id));
            $depots=User::where('user_type','employee')->where('belong',0)->get();

            return view('backend.affiliate.employee.edit', compact('depots','user','provinces'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function employee_update(Request $request, $id)
        {

            $user = User::findOrFail($id);
            $user->fill($request->all());
            $user->belong = $request->depot;
            if (strlen($request->password) > 0) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            if ($request->addresses != null) {
                Address::whereIn('id', explode(',', $request->addresses))->delete();
            }

            foreach ($request->city as $key => $city) {
                $address = Address::where('id', $request->address_id[$key])
                    ->where('user_id', $user->id)->first();

                if (!$address) {
                    $address = new Address();
                }
                   $address->name = $request->name;
                   $address->user_id = $user->id;
                   $address->province_id = $city;
                   $address->district_id = $request->district[$key];
                   $address->ward_id = $request->ward[$key];
                  $address->save();
            }

            flash('Cập nhật thông tin đại lý thành công')->success();
            return redirect()->route('affiliate.employee.index');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function employee_destroy($id)
        {
            User::destroy($id);
            flash(translate('Xóa tài khoản thành công'))->success();
            return redirect()->route('affiliate.employee.index');
        }


        public function depot_create()
        {
            $provinces=Province::all();

            return view('backend.affiliate.depot.create',compact('provinces'));
        }

        public function depot(Request $request)
        {
            $sort_search = null;
            $users = User::query()
                ->with('address_one')
                ->where('user_type', 'employee')
                ->where('belong', 0);
            if (!empty($request->search)) {
                $sort_search = $request->search;
                $users->where(function ($q) use ($sort_search) {
                    $q->where('name', 'like', "%$sort_search%")
                     ->orWhere('phone', 'like', "%$sort_search%");
                });
            }

            if ((isset($request->banned) ? $request->banned : -1) >= 0) {
                $users = $users->where('banned', $request->banned);
            }
            $users = $users-> orderBy('updated_at', 'desc') ->paginate(15);

            return view('backend.affiliate.depot.index', compact('users', 'sort_search'));
        }


        public function depot_store(Request $request)
        {
            if (User::where('phone', $request->phone)->first() == null) {
                $user = new User;
                $user->fill($request->all());
                $user->city=Province::find($request->city)->first()->name;
                $user->referral_code = $request->phone;//Str::random(10);
                $user->user_type = "employee";
                $user->belong=0;
                $user->password = Hash::make($request->password);
                $user->save();

                Address::query()->create([
                    'user_id'=>$user->id,
                    'province_id'=>$request->city,
                    'district_id'=>$request->district,
                    'ward_id'=>$request->ward,
                ]);
                flash(translate('Create Account Successfully'))->success();
                return redirect()->route('affiliate.depot.index');
            }
            flash(translate('Account already exists'))->error();
            return back();
        }

        public function depot_edit($id)
        {
            $provinces=Province::all();
            $user = User::with('address_one')->findOrFail(decrypt($id));
            return view('backend.affiliate.depot.edit', compact('user','provinces'));
        }

        public function depot_update(Request $request, $id)
        {


            $user = User::findOrFail($id);
            $user->fill($request->all());
            Address::query()->update([
                'user_id'=>$user->id,
                'province_id'=>$request->city,
                'district_id'=>$request->district,
                'ward_id'=>$request->ward,
            ]);
            if (strlen($request->password) > 0) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            flash(translate('Account has been updated successfully'))->success();
            return redirect()->route('affiliate.depot.index');
        }

        function updateToDepot( $id){
            $user=User::findOrFail(decrypt($id));
            $user->status=2;
            $user->user_type='employee';
            $user->belong=0;
            $user->provider_id=NULL;
            $user->save();
            flash(translate('Customer has been updated successfully'))->success();
//            sendFireBase($user,'Nâng cấp tài khoản','Tài khoản của bạn đã được nâng cập lên đại lý','upgrade_account');
            return redirect()->route('affiliate.depot.index');
        }


        public function depot_destroy($id)
        {
            User::destroy($id);
            flash(translate('Account deleted successfully'))->success();
            return redirect()->route('affiliate.depot.index');
        }



        public function kol(Request $request)
        {
            $sort_search = null;
            $employee = User::where('user_type', 'employee')->pluck('name', 'id');
            $users = User::where('user_type', 'kol')->orderBy('created_at', 'desc');

            if ($request->has('search')) {
                $sort_search = $request->search;
                $users->where(function ($q) use ($sort_search) {
                    $q->where('name', 'like', '%' . $sort_search . '%')->orWhere('phone', 'like', '%' . $sort_search . '%');
                });
            }
            if ((isset($request->banned) ? $request->banned : -1) >= 0) {
                $users = $users->where('banned', $request->banned);
            }
            if (!empty($request->referred_by)) {
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
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function kol_store(Request $request)
        {
            if (User::where('phone', $request->phone)->first() == null) {
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
         * @param int $id
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
         * @param \Illuminate\Http\Request $request
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function kol_update(Request $request, $id)
        {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->referred_by = $request->referred_by;
            if (strlen($request->password) > 0) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            flash(translate('Cập nhật thông tin nhân viên thành công'))->success();
            return redirect()->route('affiliate.kol.index');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function kol_destroy($id)
        {
            User::destroy($id);
            flash(translate('Xóa tài khoản thành công'))->success();
            return redirect()->route('affiliate.kol.index');
        }

        public function requestPayment()
        {

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

        public function historyPayment(Request $request)
        {
            $payments = AffiliatePayment::whereIn('status', [-1, 2]);
            if ($request->date != null) {
                $payments = $payments->where('created_time', '>=', strtotime(explode(" to ", $request->date)[0]))->where('created_time', '<=', strtotime(explode(" to ", $request->date)[1]) + 86399);
            }
            if (!empty($request->employee_id)) {
                $payments = $payments->where('user_id', $request->employee_id);
            }
            if (!empty($request->kol_id)) {
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

        public function updatePayment($id)
        {
            $payment = AffiliatePayment::where('id', $id)->where('status', 1)->first();
            $config = CommonConfig::first();
            if (!$payment) {
                return response([
                    'result' => false,
                    'message' => 'Không tìm thấy yêu cầu cần thanh toán'
                ]);
            }
            $bank=CustomerBank::where('user_id',$payment->user_id)->first();
            $wallet = Wallet::where('user_id', $payment->user_id)->first();
            $amount=config_base64_decode($wallet->amount);
            $payment->status = 2;
            $payment->payment_time = time();
            $payment->payment_user_id = auth()->id();
            $payment->save();
            $wallet->amount = config_base64_encode( config_base64_decode($wallet->amount)- $payment->amount) ;
            $wallet->updated_at=date('Y-m-d H:i:s');
            $wallet->save();
            update_customer_package($wallet->user_id);

            log_history(['type' => CustomerBillUtility::TYPE_LOG_WITHDRAW,
                'point' => -$payment->amount,
                'amount' => -(int)$payment->amount * $config->exchange,
                'amount_first' => (int)$amount,
                'amount_later' => (int)available_balances($wallet->user_id),
                'user_id' => $wallet->user_id,
                'accept_by' => Auth::user()->id,
                'content' => "Giao dịch chuyển tiền đến số thẻ $bank->number đã được duyệt"
            ]);

            $money = $payment->amount * $config->exchange;

            NewNotification([
                'type'=>CustomerBillUtility::TYPE_NOTIFICATION_PAYMENT,
                'data'=>"Bạn đã rút tiền thành công ".format_price( $money)." ", $payment->user_id,
                'user_id'=>$wallet->user_id,
                'amount_first'=>$amount,
                'amount_later'=>available_balances($wallet->user_id),
                'notifiable_type'=>CustomerBillUtility::TYPE_NOTIFICATION_USER,
            ]);
            $user=User::find($wallet->user_id);
            sendFireBase($user,'Rút Tiền','Bạn đã rút tiền thành công !','payment');


            return response([
                'result' => true,
                'message' => 'Cập nhật thanh toán thành công'
            ]);


        }

        public function cancelPayment($id, Request $request)
        {
            $config = CommonConfig::first();
            $payment = AffiliatePayment::where('id', $id)->where('status', 1)->first();
            if (!$payment) {
                return response([
                    'result' => false,
                    'message' => 'Không tìm thấy yêu cầu cần thanh toán'
                ]);
            }
            $wallet=Wallet::where('user_id',$payment->user_id)->first();
            DB::transaction(function () use ($payment, $request) {
                $user = User::find($payment->user_id);
                $user->balance = $user->balance + $payment->value;
                $user->save();
                $payment->status = -1;
                $payment->reason = $request->reason ?? '';
                $payment->save();
            });
            update_customer_package($wallet->user_id);

            log_history(['type' => CustomerBillUtility::TYPE_LOG_WITHDRAW,
                'point' => $payment->amount,
                'amount' => (int)$payment->amount * $config->exchange,
                'amount_first' => (int)config_base64_decode($wallet->amount),
                'amount_later' => (int)available_balances($wallet->user_id),
                'user_id' => $wallet->user_id,
                'accept_by' => Auth::user()->id,
                'content' => "Giao dịch bị hủy , do $request->reason"
            ]);


            NewNotification([
                'type'=>CustomerBillUtility::TYPE_NOTIFICATION_PAYMENT,
                'data'=>"Yêu cầu rút tiền của bạn đã bị hủy , nếu có thắc mắc vui lòng liên hệ với BQT ",
                'user_id'=>$wallet->user_id,
                'amount_first'=>config_base64_decode($wallet->amount),
                'amount_later'=>available_balances($wallet->user_id),
                'notifiable_type'=>CustomerBillUtility::TYPE_NOTIFICATION_USER,
            ]);
            $user=User::find($wallet->user_id);

            sendFireBase($user,'Rút Tiền','Yêu cầu rút tiền của đã bị hủy !','payment');
            return response([
                'result' => true,
                'message' => 'Hủy thanh toán thành công'
            ]);
        }


    }
