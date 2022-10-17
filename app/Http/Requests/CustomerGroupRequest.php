<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerGroupRequest extends FormRequest
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
            'bonus' => 'required',
            'can_withdraw' => 'required|numeric',
            'point_number' => 'required|numeric',
        ];
    }

    function messages()
    {
        return [
            'name.required' => 'Không để trống tên nhóm người dùng !',
            'bonus.required' => 'Không để trống tiền thưởng !',
            'can_withdraw.required' => 'Không để trống số tiền rút !',
            'point_number.required' => 'Không để trống số point !',
            'can_withdraw.numeric' => 'Số tiền rút phải có định dạng số !',
            'point_number.numeric' => 'Số point phải có định dạng số !',
             'bonus.numeric' => 'Tiền thưởng phải có định dạng số !',
        ];
    }
}
