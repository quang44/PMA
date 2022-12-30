<?php

namespace App\Http\Requests\Api\V2\WarrantyCard;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class WarrantyCardRequest extends FormRequest
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
            'user_name'=>'required',
            'address'=>'required',
            'phone'=>'required|numeric|digits:10',
            'warranty_code'=>'required|exists:warranty_codes,code|unique:warranty_cards',
        ];
    }

    function messages()
    {
        return [
            'user_name.required'=>'Vui lòng nhập tên',
            'address.required'=>'Vui lòng nhập địa chỉ',
            'phone.required'=>'Vui lòng nhập số điện thoại',
            'phone.integer'=>'Số điện thoại phải là số ',
            'phone.digits'=>'Số điện thoại không đúng định dạng ',
            'warranty_code.required'=>'Vui lòng nhập mã bảo hành',
            'warranty_code.exists'=>'Mã bảo hành không tồn tại',
            'warranty_code.unique'=>'Mã bảo hành đã được sử dụng',
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
