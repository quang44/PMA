<?php

/** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\OTPVerificationController;
use App\Http\Requests\Api\V2\Auth\AuthRequest;
use App\Models\BusinessSetting;
use App\Models\Customer;
use App\Models\CustomerPackage;
use App\Models\Wallet;
use App\Services\Extend\TelegramService;
use App\Utility\NotificationUtility;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Models\CommonConfig;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\AppEmailVerificationNotification;
use Hash;
use Illuminate\Support\Str;
use Socialite;


class AuthController extends Controller
{


    public function signup(AuthRequest $request)
    {

        $common_config = CommonConfig::first();

        $user = User::where('phone', $request->phone)->first();
        if ($user != null) {
            return response()->json([
                'result' => false,
                'message' => translate('User already exists.'),
                'data' => null
            ], 200);
        }

        $package = CustomerPackage::where('default', 1)->first();

        $user_type = $request->user_type ?? 'customer';
        $referral_code = $request->referral_code;
        $referred_by = "";
        if ($user_type == 'kol') {
            if (!empty($referral_code)) {
                $employee = User::where('user_type', 'employee')->where('referral_code', $referral_code)->first();
                if (!$employee) {
                    return response()->json([
                        'result' => false,
                        'message' => 'Không tìm thấy thông tin người giới thiệu',
                        'data' => null
                    ], 200);
                }

                $referred_by = $employee->id;
            }
        }

        if ($user_type == 'customer') {
            if (!empty($referral_code)) {
                $kol = User::with('customer_group')->where('referral_code', $referral_code)->first();
                if (!$kol) {
                    return response()->json([
                        'result' => false,
                        'message' => translate('Không tìm thấy thông tin người giới thiệu'),
                        'data' => null
                    ], 200);

                } else {

                    $walletKol = Wallet::where('user_id', $kol->id)->first();
                    $point=config_base64_decode($walletKol->amount);
                    $amount= (int)$point += $common_config->for_referrer;
                    $walletKol->amount =config_base64_encode($amount);
                    $walletKol->payment_method=translate('Hệ thống');
                    $walletKol->note=translate('Giới thiệu');
                    $walletKol->save();
                    $referred_by = $kol->id;

                }
            }
        }

        $amount=  $referred_by != "" ? $package->point_number + $common_config->for_activator : $package->bonus;
        $user = new User([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => $user_type,
            'referred_by' => $referred_by,
            'referral_code' => $request->phone,//Str::random(10),
            'balance' => $amount,
            'banned' => 0,
            'device_token' => $request->device_token,
            'email_verified_at' => date('Y-m-d H:i:s'),
            'customer_package_id' => $package->id,
            'verification_code' => rand(100000, 999999)
        ]);
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = config_base64_encode($amount);
        $wallet->payment_method=translate('Hệ thống');
        $wallet->note=translate('đăng ký tài khoản thành công');
        $wallet->save();

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
        return response()->json([
            'result' => true,
            'message' => translate('Registration Successful '),
            'data' => ['id'=>$user->id,'verification_code'=>$user->verification_code]
        ]);
        //return  $this->loginSuccess($user);
        //create token
        /*$user->createToken('tokens')->plainTextToken;

        return response()->json([
            'result' => true,
            'message' => translate('Registration Successful. Please verify and log in to your account.'),
            'user_id' => $user->id
        ], 201);*/
    }

    public function resendCode(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->verification_code = rand(100000, 999999);

//        if ($request->verify_by == 'email') {
//            $user->notify(new AppEmailVerificationNotification());
//        } else {
//            $otpController = new OTPVerificationController();
//            $otpController->send_code($user);
//        }

        $user->save();

        return response()->json([
            'result' => true,
            'message' => translate('Verification code is sent again'),
        ], 200);
    }


    public function confirmCode(Request $request)
    {
        $user = User::findOrFail( $request->user_id);

        if ($user!=null && $user->verification_code == $request->verification_code) {
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->verification_code = null;
            $user->save();
            return response()->json([
                'result' => true,
                'message' => translate('Your account is now verified.Please login'),
            ], 200);
        } else {
            return response()->json([
                'result' => false,
                'message' => translate('Code does not match, you can request for resending the code'),
            ], 200);
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
                    return response()->json(['result' => false, 'message' => translate('Tài khoản đã bị khóa'), 'data' => null], 401);

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
                return response()->json(['result' => false, 'message' => translate('Tài khoản hoặc mật khẩu không chính xác'), 'data' => null], 401);
            }
        } else {
            return response()->json(['result' => false, 'message' => translate('User not found'), 'data' => null], 401);
        }
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
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
                'message' => translate('Your password is reset.Please login'),
            ], 200);
        } else {
            return response()->json([
                'result' => false,
                'message' => translate('User not found'),
            ], 200);
        }
    }

    public function user(Request $request)
    {
        $user = auth()->user();
        return response()->json([
            'result' => true,
            'data' => [
                'id' => $user->id,
                'type' => $user->user_type,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'avatar_original' => uploaded_asset($user->avatar_original),
                'phone' => $user->phone,
                'referred_by' => $user->referred_by,
                'referral_code' => $user->referral_code,
//                'balance' => $user->balance,
//                'best_api_user' => $user->best_api_user,
                'created_at' => date('d-m-Y H:i:s',strtotime($user->created_at))
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

    public function update(Request $request)
    {

        //$user = User::findOrFail($request->user_id);
        $user = auth()->user();
        $data = [];
        if (!empty($request->name)) {
            $data['name'] = $request->name;
        }
        if (!empty($request->email)) {
            $data['email'] = $request->email;
        }
        $user->update($data);
        return response()->json([
            'result' => true,
            'message' => translate('Profile information has been updated successfully')
        ]);
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
        $user->banned = 1;
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
                    'type' => $user->user_type,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'avatar_original' => uploaded_asset($user->avatar_original),
                    'phone' => $user->phone,
                    'referred_by' => $user->referred_by,
                    'referral_code' => $user->referral_code,
                    'balance' => $user->balance,
                    'best_api_user' => $user->best_api_user,
                    'created_at' => date('d-m-Y H:i:s', strtotime($user->created_at))
                ]
            ]
        ]);
    }
}
