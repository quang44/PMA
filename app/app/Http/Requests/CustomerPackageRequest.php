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
            'bonus.required' => 'Không để trống điểm thưởng của nhóm người dùng !',
            'bonus.numeric' => 'Điểm thưởng của nhóm người dùng phải là một số !',
            'withdraw.required' => 'Không để trống số có thể tiền rút của nhóm người dùng !',
            'point.required' => 'Không để trống số point !',
            'withdraw.numeric' => 'Số tiền có thể rút phải là một số !',
            'point.numeric' => 'Số point phải là một số !',
        ];
    }
}
