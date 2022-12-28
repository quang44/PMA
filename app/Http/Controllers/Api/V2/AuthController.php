<?php

    /** @noinspection PhpUndefinedClassInspection */

    namespace App\Http\Controllers\Api\V2;

    use App\Http\Controllers\OTPVerificationController;
    use App\Http\Requests\Api\V2\Auth\AuthRequest;
    use App\Http\Resources\V2\NotificationCollection;
    use App\Models\Address;
    use App\Models\CommonConfig;
    use App\Models\CustomerPackage;
    use App\Models\Notification;
    use App\Models\User;
    use App\Models\Wallet;
    use App\Utility\CustomerBillUtility;
    use App\Utility\NotificationUtility;
    use Hash;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;


    class AuthController extends Controller
    {

        function checkPhone(Request $request)
        {
            if($request->type=='reset'){
                $user=User::query()->where('phone',$request->phone)->first();
                if(!$user){
                    return response([
                        'message' => 'Số điện thoại không tồn tại',
                        'result' => false
                    ]);
                }
            }else{
                $validate = Validator::make($request->all(), [
                    'phone' => 'required|unique:users|digits:10'
                ], [
                    'phone.required' => 'Số điện thoại không được để trống',
                    'phone.unique' => 'Số điện thoại đã được sử dụng',
                    'phone.digits' => 'Số điện thoại không hợp lệ',
                ]);
                if ($validate->fails()) {
                    return response([
                        'message' => $validate->errors()->first(),
                        'result' => false
                    ]);
                }
            }


            return response([
                'message' => 'Số điện thoại hợp lệ',
                'result' => true
            ]);



        }


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

                        $walletKol = addWallet($kol->id);
                        $point = config_base64_decode($walletKol->amount);
                        $amount = $point + $common_config->for_referrer;
                        log_history($data = ['type' => CustomerBillUtility::TYPE_LOG_ADDITION,
                            'point' => (int)$amount,
                            'amount' => (int)$amount * $common_config->exchange,
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
            $address = Address::query()->where('user_id', $request->depot)->first();

            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'belong' => $request->depot,
                'password' => Hash::make($request->password),
                'user_type' => $user_type,
                'referred_by' => $referred_by,
                'referral_code' => $request->phone,//Str::random(10),
                'email_verified_at' => now(),
                'address' => $request->address,
                'customer_package_id' => $package->id,
            ]);
            $user->save();

            if ($address) {
                Address::query()->create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'province_id' =>  $address->province_id ?:null,
                    'district_id' =>  $address->district_id ?: null,
                    'ward_id' =>  $address->ward_id ?: null,
                ]);
            }


            $amount = $referred_by != 0 ? $package->bonus + $common_config->for_activator : $package->bonus;
            addWallet($user->id, $amount);

            return $this->createSuccess($user);

        }


        public function login(Request $request)
        {


            $user = User::query()->where('phone', $request->phone)->first();
            if ($user != null) {
                if (Hash::check($request->password, $user->password)) {
                    if ($user->banned == 1) {
                        return response()->json(['result' => false, 'message' => 'Tài khoản đã tạm thời bị khóa', 'data' => null], 401);
                    }
                    if ($request->device_token) {
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
            $user = auth()->user();
            if (Hash::check($request->password_old, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json(['result' => true, 'message' => translate('Successfully'), 'data' => null]);
            }
            return response()->json(['result' => false, 'message' => translate('Password wrong'), 'data' => null]);
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
            $user = User::query()->with('user_agent', 'address_one', 'addresses')->findOrFail($auth->id);

            return response()->json([
                'result' => true,
                'data' => [
                    'id' => $auth->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar == null ? Null : static_asset($user->avatar),
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'depot' => $user->belong != null && $user->user_agent!=null ? $user->user_agent->name : null,
                    'referral_code' => $user->referral_code,
                    'address' => $user->addresses,
//                    'package' => $user->customer_package != null ? $user->customer_package->name : null,
//                    'logoPackage' => $user->customer_package != null ? uploaded_asset($user->customer_package->avatar) : null,
                    'created_at' => date('d-m-Y H:i:s', strtotime($user->created_at))
                ]
            ]);
        }

//    public function notification()
//    {
//        $user = auth()->user();
//        if (!empty($user->device_token)) {
//            $req = new \stdClass();
//            $req->device_token = $user->device_token;
//            $req->title = "Kích hoạt tài khoản !";
//            $req->text = "Tài khoản của bạn đã được kích hoạt";
//
//            $req->type = "active_user";
//            $req->id = $user->id;
//            $req->best_api_user = $user->best_api_user;
//            $result = NotificationUtility::sendFirebaseNotification($req);
//            return response(['result' => true, 'data' => $result]);
//        } else {
//            return response(['result' => false]);
//        }
//    }

        public function AuthNotification(Request $request)
        {
            $user = auth()->user();
            $Notification = Notification::query()
                ->with(['card','gifts'])
                 ->where('user_id', $user->id)
                ->orWhere('send_group',$user->belong)
                ->orWhere(function ($query){
                    $query->where('send_group',0);
                })
                ->orderBy('updated_at', 'DESC')
                ->paginate($request->limit ?? 10);
//dd($Notification->toArray());
            return new NotificationCollection($Notification);
        }

        function AuthNotificationDetail($id)
        {
            $Notification = Notification::query()
                ->with(['card.province','card.district','card.ward','gifts'])
                ->findOrFail($id);
            $Notification->read_at=strtotime(now());
            $Notification->save();
            $Notification-> title=CustomerBillUtility::$arrayTypeNotification[$Notification->type];
            $Notification->content=$Notification->data;
            $Notification->date=convertTime($Notification->created_at);
            $Notification->makeHidden(['gifts', 'card', 'read_at','created_at','updated_at','notifiable_id','send_group','data','notifiable_type']);
      $data=[];

        if($Notification->gifts){
            $data=[
                'username'=>$Notification->gifts->user->name,
                'name'=>$Notification->gifts->gift->name,
                'status'=>$Notification->gifts->status,
                'reason'=>$Notification->gifts->reason,
                'accept_by'=>!$Notification->accept?null: $Notification->accept->name,
                'point'=>$Notification->gifts->gift->point,
                'address'=>$Notification->gifts->address
            ];
        }

            if($Notification->card){
                $product='';
                foreach ($Notification->card->cardDetail as $item){
                    $name=$item->product->name;
                    $product.=$name.',';
                }
                 $address=$Notification->card->address.', '.$Notification->card->ward->name.', '.$Notification->card->district->name.', '.$Notification->card->province->name;
                $data=[
                    'username'=>$Notification->card->user_name,
                    'name'=> rtrim($product,','),
                    'status'=>$Notification->card->status,
                    'reason'=>$Notification->card->reason,
                    'accept_by'=>!$Notification->active_user_id?null: $Notification->active_user_id->name,
                    'point'=>$Notification->card->point,
                    'address'=>$address
                ];
            }
            $Notification->item=$data;
//            dd($data);

            return response([
                'data'=>$Notification,
                'result'=>true,
            ]);
        }

        function  countNotification(){
            $notification=Notification::query()
                ->where('user_id',auth()->user()->id)
             ->whereNull('read_at')->count();
            return $this->sendSuccess($notification);
        }

        public function update(Request $request)
        {

            $user = User::findOrFail(auth()->user()->id);
            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required|unique:users,phone,' . $user->id,
                'email' => 'email|unique:users,email,' . $user->id,
            ]);
            if ($validate->fails()) {
                return response([
                    'result' => false,
                    'message' => $validate->errors()->first()
                ]);
            }


            $user->fill($request->all());
            if (!empty($request->password_old)) {
                if (Hash::check($request->password_old, $user->password)) {
                    $user->password = Hash::make($request->password);
                } else {
                    return $this->sendError(translate('Password wrong'));
                }
            }

            $user->phone=$request->phone;
            $user->email=$request->email;

            if ($request->avatar) {
//               if( $request->avatar->getSize()>10000){
//                   return $this->sendError("Ảnh phải nhỏ hơn 10mb");
//               }
                if ($user->avatar != null) {
                    removeImg($user->avatar);
                }
                $newPath = uploadFile($request->avatar, 'uploads/all');
                $user->avatar = $newPath;
            }

            if ($request->depot) {
                $user->belong=$request->depot;
                $address = Address::query()->where('user_id', $request->depot)->first();
                if ($address) {
                    Address::create([
                        'user_id' => $user->id,
                        'name' => $request->name,
                        'address' => $request->address,
                        'phone' => $request->phone,
                        'province_id' => $address ? $address->province_id : null,
                        'district_id' => $address ? $address->district_id : null,
                        'ward_id' => $address ? $address->ward_id : null,
                    ]);
                }
            }
            $user->save();

            $user->makeHidden([
                    "user_type", "referred_by", "belong", "provider_id", "email_verified_at", "verification_code", "new_email_verificiation_code", "device_token", "avatar_original", "address", "country", "city", "postal_code",
                    "balance", "status", "banned", "customer_package_id", "remaining_uploads", "best_api_user", "best_api_password", "best_api_token", "bank_updated", "updated_by", "rules_accept", "created_at", "updated_at"
                ]
            );
            $user->avatar = static_asset($user->avatar);
            return $this->updateSuccess($user,'Thông tin của bạn đã được cập nhật thành công');
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
                        'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => static_asset($user->avatar),

                        'phone' => $user->phone,
                        'referred_by' => $user->referred_by,
                        'referral_code' => $user->referral_code,

                        'created_at' => date('d-m-Y H:i:s', strtotime($user->created_at))
                    ]
                ]
            ]);
        }

        public function balances()
        {
            $available_balances = available_balances(auth()->user()->id);
//            $customerPackage = CustomerPackage::whereBetween('point', [0, $available_balances])->first();
//            $checkCustomer = User::where('customer_package_id', $customerPackage->id)->first();
//            $wallet=Wallet::where('user_id',auth()->user()->id)->first();
            return $this->sendSuccess($available_balances);
        }
    }
