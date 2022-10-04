<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\CustomerBank\CreateRequest;
use App\Models\Bank;
use App\Models\CustomerBank;
use App\Services\Extend\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerBankController extends Controller
{
    public function __construct()
    {
    }

    public function show(){
        $user_id = auth()->id();
        $data = CustomerBank::where('user_id', $user_id)->first();
        return response([
            'result' => true,
            'data' => $data,
        ]);
    }

    public function store(CreateRequest $request){
        $param = $request->only(['name', 'branch', 'username', 'number', 'password']);
        $user_id = auth()->id();
        $user = auth()->user();
        $bank = CustomerBank::where('user_id', $user_id)->first();
        if($bank){
            if(!Hash::check($request->password, $user->password)){
                return response([
                    'result' => false,
                    'message' => translate('Mật khẩu không chính xác')
                ]);
            }
        }

        $bank = CustomerBank::updateOrCreate(
            ['user_id' => $user_id],
            $param
        );
        $user->bank_updated = 1;
        $user->save();
        $text = '
            <b>[Nguồn] : </b><code>GomDon</code>
            <b>[Tiêu đề] : </b><code>Khách cập nhật thông tin tài khoản ngân hàng</code>
            <b>[Mô tả] : </b><a href="' . route('customers.index', ['search' => $user->name]) . '">Xem chi tiết</a>';
        TelegramService::sendMessageGomdon($text);
        return response([
            'result' => true,
            'data' => $bank,
            'message' => translate('Bank account has been created successfully')
        ]);
    }

    public function index(){
        $bank = Bank::all();
        foreach ($bank as $key => $value){
            $value['icon'] = uploaded_asset($value->icon);
            $bank[$key] = $value;
        }
        return response([
            'result' => true,
            'data' => $bank,
        ]);
    }
}
