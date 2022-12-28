<?php

namespace App\Http\Requests\Api\V2\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AuthRequest extends FormRequest
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
            'name'=>'required',
            'phone'=>'required|unique:users|digits:10',
            'password'=>'required|min:6',
            'password_confirmation'=>'same:password',
            'rules_accept'=>'required',
            'email'=>'required|email'
        ];
    }

    function messages()
    {
        return [
            'rules_accept.required'=>'Vui lòng chấp nhận điều khoản của chúng tôi để tiếp tục',
            'name.required'=>'Vui lòng nhập tên',
            'phone.required'=>'Vui lòng nhập số điện thoại',
            'phone.unique'=>'Số điện thoại đã được sử dụng',
            'phone.digits'=>'Số điện thoại không hợp lệ',
            'password.required'=>'Vui lòng nhập nhập khẩu',
            'password.min'=>'Mật khẩu chứa ít 6 ký tự trở lên',
            'password_confirmation.same'=>'Mật khẩu không khớp',
            'email.required'=>'Vui lòng nhập email',
            'email.email'=>'Email không đúng định dạng'
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $json = [
            'result' => false,
            'message' => $validator->errors()->first()
        ];
        $response = response( $json );
        throw new ValidationException($validator, $response);
    }
}
