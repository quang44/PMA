<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerPackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'bonus' => 'required|numeric',
            'withdraw' => 'required|numeric',
            'point' => 'required|numeric',
        ];
    }

    function messages()
    {
        return [
            'name.required' => 'Không để trống tên nhóm người dùng !',
            'bonus.required' => 'Không để trống tiền thưởng !',
            'withdraw.required' => 'Không để trống số tiền rút !',
            'point.required' => 'Không để trống số point !',
            'withdraw.numeric' => 'Số tiền rút phải có định dạng số !',
            'point.numeric' => 'Số point phải có định dạng số !',
            'bonus' => 'Tiền thưởng phải có định dạng số !',
        ];
    }
}
