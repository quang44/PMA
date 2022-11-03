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


//        |regex:/^[a-zA-Z_ ]*$/
        return [
            'user_name'=>'required|max:255',
            'address'=>'required',
            'seri'=>'required|numeric|unique:warranty_cards',
            'brand'=>'required',
            'image'=>'required',
        ];
    }

    function messages()
    {
        return [
            'user_name.required'=>'vui lòng nhập tên khách hàng',
            'address.required'=>'vui lòng nhập địa chỉ',
            'seri.required'=>'vui lòng nhập số seri',
            'seri.numeric'=>'Trường seri phải là số',
            'seri.unique'=>' Số seri đã được sử dụng ',
            'brand_id.required'=>'vui lòng chọn hãng sản xuất  ',
            'image.required'=>'vui lòng chọn ảnh',
            'image.image'=>'Trường ảnh không phải 1 ảnh',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $json = [
            'result' => false,
            'message' => $validator->errors()
        ];
        $response = response( $json );
        throw new ValidationException($validator, $response);
    }

}
