<?php

namespace App\Http\Controllers\Api\V2;

use App\Notifications\AppEmailVerificationNotification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordReset;
use App\Notifications\PasswordResetRequest;
use Illuminate\Support\Str;
use App\Http\Controllers\OTPVerificationController;

use Hash;

class PasswordResetController extends Controller
{
    public function forgetRequest(Request $request)
    {
//        if ($request->send_by == 'email') {
//            $user = User::where('email', $request->email_or_phone)->first();
//        } else {
            $user = User::where('phone', $request->phone)->first();
//        }


        if (!$user) {
            return response()->json([
                'result' => false,
                'message' => translate('Tài khoản không tồn tại')], 404);
        }

//        if ($user) {
//            $user->verification_code = rand(100000, 999999);
//            $user->save();
//            if ($request->send_by == 'phone') {
//
//                $otpController = new OTPVerificationController();
//                $otpController->send_code($user);
//            } else {
//                $user->notify(new AppEmailVerificationNotification());
//            }
//        }

        return response()->json([
            'result' => true,
            'message' => translate('A code is sent')
        ], 200);
    }

    public function get_OTP_code( Request $request){
        User::where('phone', $request->phone)->update([
         'verification_code',$request->verification_code
        ]);
    }

    public function confirmReset(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            $user->verification_code = null;
            $user->password = Hash::make($request->password);
            $user->save();
            return $this->updateSuccess($user,'Mật khẩu  của bạn đã được thay đổi thành công') ;
        } else {
            return $this->sendError(translate('No user is found'));
        }
    }

    public function resendCode(Request $request)
    {

//        if ($request->verify_by == 'email') {
//            $user = User::where('email', $request->email_or_phone)->first();
//        } else {
            $user = User::where('phone', $request->phone)->first();
//        }


        if (!$user) {
            return response()->json([
                'result' => false,
                'message' => translate('Tài khoản không tồn tại')], 404);
        }

//        $user->verification_code = rand(100000, 999999);
//        $user->save();

//        if ($request->verify_by == 'email') {
//            $user->notify(new AppEmailVerificationNotification());
//        } else {
//            $otpController = new OTPVerificationController();
//            $otpController->send_code($user);
//        }



        return response()->json([
            'result' => true,
            'message' => translate('A code is sent again'),
        ], 200);
    }
}
