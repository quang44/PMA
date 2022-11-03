<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'name' => 'required|string',
            'phone' => 'required|numeric|unique:users',
//            'customer_package_id' => 'required',
            'password' => 'required|min:6|confirmed',
        ];
    }

    public  function messages()
    {
        return [
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'phone.numeric' => 'Trường điện thoại phải là số ',
//            'customer_package_id.required' => 'Vui lòng chọn nhóm ',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự',
            'password.confirmed' => 'Vui lòng xác nhận mật khẩu',
        ];
    }
}
