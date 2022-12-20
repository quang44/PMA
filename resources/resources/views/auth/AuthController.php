<?php

    /** @noinspection PhpUndefinedClassInspection */

    namespace App\Http\Controllers\Api\V2;

    use App\Http\Controllers\OTPVerificationController;
    use App\Http\Requests\Api\V2\Auth\AuthRequest;
    use App\Http\Resources\V2\LogCollection;
    use App\Http\Resources\V2\NotificationCollection;
    use App\Models\Address;
    use App\Models\BusinessSetting;
    use App\Models\Customer;
    use App\Models\CustomerPackage;
    use App\Models\Log;
    use App\Models\Notification;
    use App\Models\Wallet;
    use App\Services\Extend\TelegramService;
    use App\Utility\CustomerBillUtility;
    use App\Utility\NotificationUtility;
    use Illuminate\Http\Request;
    use App\Models\CustomerGroup;
    use App\Models\CommonConfig;
    use Carbon\Carbon;
    use App\Models\User;
    use App\Notifications\AppEmailVerificationNotification;
    use Hash;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Str;
    use Socialite;


    class AuthController extends Controller
    {


        public function signup(AuthRequest $request)
        {

            $common_config = CommonConfig::first();

            $user = User::where('phone', $request->phone)->first();
            if ($user != null) {
                return $this->sendError('Số điện thoại đã được sử dụng');
            }

            $package = CustomerPackage::where('default', 1)->first();

            $user_type = $request->user_type ?? 'customer';
            $referral_code = $request->referral_code;
            $referred_by = "";
            if ($user_type == 'kol') {
                if (!empty($referral_code)) {
                    $employee = User::where('user_type', 'employee')->where('referral_code', $referral_code)->first();
                    if (!$employee) {
                        return $this->sendError('không tìm thấy người giới thiệu');
                    }

                    $referred_by = $employee->id;
                }
            }

            if ($user_type == 'customer') {
                if (!empty($referral_code)) {
                    $kol = User::with('customer_group')->where('referral_code', $referral_code)->first();
                    if (!$kol) {
                        return $this->sendError('không tìm thấy người giới thiệu');

                    } else {

                        $walletKol = Wallet::where('user_id', $kol->id)->first();
                        $point = config_base64_decode($walletKol->amount);
                        $amount = $point + $common_config->for_referrer;
                        log_history($data = ['type' => CustomerBillUtility::TYPE_LOG_ADDITION,
                            'point' => (int)$amount,
                            'amount' => (int)$amount * $common_config->exchange,
                            'object' => 0,
                            'amount_first' => $point,
                            'amount_later' => (int)available_balances($walletKol->user_id),
                            'user_id' => $user->id,
                            'content' => "Giới thiệu thành công"
                        ]);

                        $walletKol->amount = config_base64_encode($amount);
                        $walletKol->payment_method = translate('Hệ thống');
                        $walletKol->save();
                        $referred_by = $kol->id;


                    }
                }
            }

//     $dataAccount=[
//         'name' => $request->name,
//         'phone' => $request->phone,
//         'password' => bcrypt($request->password),
//         'user_type' => $user_type,
//         'referred_by' => $referred_by,
//         'referral_code' => $request->phone,//Str::random(10),
//         'banned' => 0,
//         'email_verified_at' => date('Y-m-d H:i:s'),
//         'customer_package_id' => $package->id,
//     ];
            $user = new User([
                'name' => $request->name,
                'email'=>$request->email,
                'phone' => $request->phone,
                'provider_id'=>$request->depot,
                'password' => Hash::make($request->password),
                'user_type' => $user_type,
                'referred_by' => $referred_by,
                'referral_code' => $request->phone,//Str::random(10),
                'email_verified_at' => date('Y-m-d H:i:s'),
                'customer_package_id' => $package->id,
            ]);
            $user->save();
            Address::create([
                'user_id'=>$user->id,
                'name'=>$request->name,
                'phone'=>$request->phone,
                'province_id'=>$request->province,
                'district_id'=>$request->district,
                'ward_id'=>$request->ward,
            ]);

//        session()->put('account',$dataAccount);


            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $amount = $referred_by != 0 ? $package->bonus + $common_config->for_activator : $package->bonus;
            $wallet->amount = config_base64_encode($amount);
            $wallet->save();

//            log_history($data = ['type' => CustomerBillUtility::TYPE_LOG_ADDITION,
//                'point' => (int)$amount,
//                'amount' => (int)$amount * $common_config->exchange,
//                'object' => 0,
//                'amount_first' => 0,
//                'amount_later' => (int)config_base64_decode($wallet->amount),
//                'user_id' => $user->id,
//                'content' => "Đăng ký tài khoản thành công"
//            ]);


            /*if ($request->register_by == 'email') {
                $user = new User([
                    'name' => $request->name,
                    'email' => $request->email_or_phone,
                    'password' => bcrypt($request->password),
                    'user_type' => 'customer',
                    'device_token' => $request->device_token,
                    'email_verified_at' => date('Y-m-d H:i:s'),
                    'verification_code' => null//rand(1000, 9999)
                ]);
            } else {

            }*/

            /*$user->email_verified_at = null;
            if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
            }*/

            /*if($user->email_verified_at == null){
                if ($request->register_by == 'email') {
                    try {
                        $user->notify(new AppEmailVerificationNotification());
                    } catch (\Exception $e) {
                    }
                } else {
                    $otpController = new OTPVerificationController();
                    $otpController->send_code($user);
                }
            }*/


//        $text = '
//            <b>[Nguồn] : </b><code>GomDon</code>
//            <b>[Tiêu đề] : </b><code>Khách hàng mới</code>
//            <b>[Mô tả] : </b><a href="' . route('customers.index', ['search' => $user->name]) . '">Xem chi tiết</a>';
//        TelegramService::sendMessageGomdon($text);
            return $this->createSuccess($user);
            //return  $this->loginSuccess($user);
            //create token
            /*$user->createToken('tokens')->plainTextToken;

            return response()->json([
                'result' => true,
                'message' => translate('Registration Successful. Please verify and log in to your account.'),
                'user_id' => $user->id
            ], 201);*/
        }

//    public function resendCode(Request $request)
//    {
//        $user = User::where('id', $request->user_id)->first();
//        $user->verification_code = rand(100000, 999999);
//        if ($request->verify_by == 'email') {
//            $user->notify(new AppEmailVerificationNotification());
//        } else {
//            $otpController = new OTPVerificationController();
//            $otpController->send_code($user);
//        }
//
//        $user->save();
//
//        return response()->json([
//            'result' => true,
//            'message' => translate('Mã xác minh được gửi lại'),
//        ], 200);
//    }

        function getOPt(Request $request)
        {
       User::where('phone',$request->phone)->update([
            'verification_code'=>$request->verification_code
        ]);

        }


        public function confirmCode(Request $request)
        {
            $user = User::where('phone',$request->phone)->first();

            if ($user != null && $user->verification_code == $request->verification_code) {
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->verification_code = null;
                $user->banned = 0;
                $user->save();
                return $this->sendSuccess('Tài khoản của bạn  đã được xác thực ,vui lòng đăng nhập');
            } else {
//            $user->delete();
                return $this->sendError('Mã không khớp, bạn có thể yêu cầu gửi lại mã');
            }
        }

        public function login(Request $request)
        {
            /*$request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ]);*/

            /*$delivery_boy_condition = $request->has('user_type') && $request->user_type == 'delivery_boy';

            if ($delivery_boy_condition) {
                $user = User::whereIn('user_type', ['delivery_boy'])->where('email', $request->email)->orWhere('phone', $request->email)->first();
            } else {
                $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->orWhere('phone', $request->email)->first();
            }

            if (!$delivery_boy_condition) {
                if (\App\Utility\PayhereUtility::create_wallet_reference($request->identity_matrix) == false) {
                    return response()->json(['result' => false, 'message' => 'Identity matrix error', 'user' => null], 401);
                }
            }*/

            $user = User::where('phone', $request->phone)->first();

            if ($user != null) {
                if (Hash::check($request->password, $user->password)) {

                    /* if ($user->email_verified_at == null) {
                         return response()->json(['result' => false, 'message' => translate('Please verify your account'), 'data' => null], 401);
                     }*/
                    if ($user->banned == 1) {
                        return response()->json(['result' => false, 'message' => 'Tài khoản đã tạm thời bị khóa', 'data' => null], 401);

                    }
                    if ($request->device_token) {
//                    return response([
//                        'token'=>$request->device_token
//                    ]);
                        $user->device_token = $request->device_token;
                        $user->save();
                    }

                    return $this->loginSuccess($user);
                } else {
                    return response()->json(['result' => false, 'message' => 'Tài khoản hoặc mật khẩu không chính xác', 'data' => null], 401);
                }
            } else {
                return response()->json(['result' => false, 'message' => 'Tài khoản không tồn tại', 'data' => null], 401);
            }
        }

        public function changePassword(Request $request)
        {
            $validate = Validator::make($request->all(), [
                'password_old' => 'required',
            ], [
                'password_old.required' => 'vui lòng nhập mật khẩu',
            ]);
            if ($validate->fails()) {
                return response([
                    'result' => false,
                    'message' => $validate->errors()
                ]);
            }

            $user = auth()->user();
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                return response()->json(['result' => true, 'message' => translate('Thay đổi mật khẩu thành công'), 'data' => null]);
            }
            return response()->json(['result' => false, 'message' => translate('Sai mật khẩu,vui lòng nhập lại'), 'data' => null]);
        }

        public function resetPassword(Request $request)
        {
            $user = User::where('phone', $request->phone)->first();
            if ($user != null) {
                $user->verification_code = null;
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json([
                    'result' => true,
                    'message' => translate('Mật khẩu của bạn đã được thay đổi'),
                ], 200);
            } else {
                return response()->json([
                    'result' => false,
                    'message' => translate('Tài khoản không tồn tại'),
                ], 200);
            }
        }

        public function user(Request $request)
        {
            $auth = auth()->user();
            $user=User::query()->with('user_agent','address_one')->findOrFail($auth->id);
            return response()->json([
                'result' => true,
                'data' => [
                    'name' => $user->name,
                    'avatar' =>$user->avatar==null?Null:static_asset($user->avatar) ,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'depot'=>$user->user_agent->name,
                    'referred_by' => $user->referred_customer != null ? $user->referred_customer->name : null,
                    'referral_code' => $user->referral_code,
                    'address'=>$user->address_one,
                    'package' => $user->customer_package != null ? $user->customer_package->name : null,
                    'logoPackage' => $user->customer_package != null ? uploaded_asset($user->customer_package->avatar) : null,
                    'created_at' => date('d-m-Y H:i:s', strtotime($user->created_at))
                ]
            ]);
        }

    public function notification()
    {
        $user = auth()->user();
        if (!empty($user->device_token)) {
            $req = new \stdClass();
            $req->device_token = $user->device_token;
            $req->title = "Kích hoạt tài khoản !";
            $req->text = "Tài khoản của bạn đã được kích hoạt";

            $req->type = "active_user";
            $req->id = $user->id;
            $req->best_api_user = $user->best_api_user;
            $result = NotificationUtility::sendFirebaseNotification($req);
            return response(['result' => true, 'data' => $result]);
        } else {
            return response(['result' => false]);
        }
    }

        public function AuthNotification()
        {
            $user = auth()->user();
            $Notification = Notification::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
            return new NotificationCollection($Notification);
        }


        public function update(Request $request)
        {
        return ['data'=>json_decode(json_encode($request->province,true))];
            $user = auth()->user();
            $validate=Validator::make($request->all(),[
                'name'=>'required',
                'phone'=>'required|unique:users,phone,'.$user->id,
                'email'=>'required|email|unique:users,email,'.$user->id,
            ]);
            if($validate->fails()){
                return response([
                    'result'=>false,
                    'message'=>$validate->errors()->first()
                ]);
            }

            //$user = User::findOrFail($request->user_id);



            $data = [];
          $user->fill($request->except('password','avatar'));
            if (!empty($request->password_old) && Hash::check($request->password_old,$user->password)) {
                $data['password'] = Hash::make($request->password);
            }else{
                return $this->sendError( translate('Password wrong'));
            }

            if ($request->avatar && $request->avatar != null) {
                if($user->avatar!=null){
                    if (file_exists(public_path('').'/'.$user->avatar)) {
                        unlink(public_path('').'/'.$user->avatar);
                    }
                }
                $image=$request->avatar;
                $realImage = $image->hashName();
                $path='uploads/all/';
                $newPath = $path . "$realImage";
                $image->store($path, 'local');
                $data['avatar']=$newPath;

            }
            foreach ($request->province as $key=>$city){
                $address=Address::where('id',$request->address_id[$key])->where('user_id',$user->id)->first();
                if($address){
                    $address->update([
                        'name'=>$request->name,
                        'province_id'=>$city,
                        'district_id'=>$request->district[$key],
                        'ward_id'=>$request->ward[$key],
                    ]);
                }else{
                    Address::create([
                        'name'=>$request->name,
                        'user_id'=>$user->id,
                        'province_id'=>$city,
                        'district_id'=>$request->district[$key],
                        'ward_id'=>$request->ward[$key],
                    ]);
                }

            }

            $user->save();



            return response()->json([
                'result' => true,
                'message' => translate('Profile information has been updated successfully')
            ]);
        }

        function update_to_agent( ){
            $user=User::findOrFail(auth()->id());
            $user->status=1;
            $user->save();
            return $this->sendSuccess(null);
        }

        function update_to_depot( ){
            $user=User::findOrFail(auth()->id());
            $user->status=1;
            $user->provider_id=0;
            $user->save();
            return $this->sendSuccess(null);
        }

        public function logout(Request $request)
        {
            $request->user()->tokens()->delete();

            return response()->json([
                'result' => true,
                'message' => translate('Successfully logged out')
            ]);
        }

        public function destroy(Request $request)
        {
            $user = auth()->user();
            if (!$user) {
                return response(['result' => false, 'message' => translate('Không tìm thấy thông tin tài khoản')]);
            }
            $user->banned = 2;
            $user->save();
            return response([
                'result' => true,
            ]);
        }

        /*public function socialLogin(Request $request)
        {
            if (!$request->provider) {
                return response()->json([
                    'result' => false,
                    'message' => translate('User not found'),
                    'user' => null
                ]);
            }

            //
            switch ($request->social_provider) {
                case 'facebook':
                    $social_user = Socialite::driver('facebook')->fields([
                        'name',
                        'first_name',
                        'last_name',
                        'email'
                    ]);
                    break;
                case 'google':
                    $social_user = Socialite::driver('google')
                        ->scopes(['profile', 'email']);
                    break;
                default:
                    $social_user = null;
            }
            if ($social_user == null) {
                return response()->json(['result' => false, 'message' => translate('No social provider matches'), 'user' => null]);
            }

            $social_user_details = $social_user->userFromToken($request->access_token);

            if ($social_user_details == null) {
                return response()->json(['result' => false, 'message' => translate('No social account matches'), 'user' => null]);
            }

            //

            $existingUserByProviderId = User::where('provider_id', $request->provider)->first();

            if ($existingUserByProviderId) {
                return $this->loginSuccess($existingUserByProviderId);
            } else {
                $user = new User([
                    'name' => $request->name,
                    'email' => $request->email,
                    'provider_id' => $request->provider,
                    'email_verified_at' => Carbon::now()
                ]);
                $user->save();
            }
            return $this->loginSuccess($user);
        }*/

        protected function loginSuccess($user)
        {
            $token = $user->createToken('API Token')->plainTextToken;
            return response()->json([
                'result' => true,
                'message' => translate('Successfully logged in'),
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_at' => null,
                    'user' => [
                        'id' => $user->id,

//                    'type' => $user->user_type,
                        'name' => $user->name,
//                    'email' => $user->email,
//                    'avatar' => $user->avatar,
//                    'avatar_original' => uploaded_asset($user->avatar_original),
                        'phone' => $user->phone,
                        'referred_by' => $user->referred_by,
                        'referral_code' => $user->referral_code,
//                    'balance' => $user->balance,
//                    'best_api_user' => $user->best_api_user,
                        'created_at' => date('d-m-Y H:i:s', strtotime($user->created_at))
                    ]
                ]
            ]);
        }

        public function balances()
        {
            $available_balances = available_balances(auth()->user()->id);
            $customerPackage = CustomerPackage::whereBetween('point', [0, $available_balances])->first();
            $checkCustomer = User::where('customer_package_id', $customerPackage->id)->first();
            $wallet=Wallet::where('user_id',auth()->user()->id)->first();
            return response()->json([
                'balance' => $available_balances,
//                'package' => $customerPackage,
//                'user' => $checkCustomer
            'wallet'=>config_base64_decode($wallet->amount)
            ]);
        }
    }
