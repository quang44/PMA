<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'seri'=>'required|numeric',
            'brand_id'=>'required',
            'image'=>'required'
        ];
    }

    function messages()
    {
        return [
            'user_name.required'=>'vui lòng nhập tên khách hàng',
            'address.required'=>'vui lòng nhập địa chỉ',
            'seri.required'=>'vui lòng nhập số seri',
            'seri.numeric'=>'seri phải là số',
             'brand_id.required'=>'vui lòng chọn hãng sản xuất  ',
            'image.required'=>'vui lòng chọn ảnh',

        ];
    }
}
