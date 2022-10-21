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
            'email' => 'nullable|string|email',
            'name' => 'required|string',
            'phone' => 'required|string|unique:users',
            'customer_package_id' => 'required',
            'customer_group_id' => 'required',
            'password' => 'nullable|string|min:6|confirmed',
        ];
    }

    public  function messages()
    {
        return [
            'email.email' => 'Email không đúng định dạng',
            'name.required' => 'Vui lòng nhập tên ',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'customer_package_id.required' => 'Vui lòng chọn gói',
            'customer_group_id.required' => 'Vui lòng chọn nhóm',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự',
            'password.confirmed' => 'Vui lòng xác nhận mật khẩu',
        ];
    }
}
