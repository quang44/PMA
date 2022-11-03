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
            'seri'=>'required|numeric|unique:warranty_cards',
            'brand_id'=>'required',
            'image'=>'required|image'
        ];
    }

    function messages()
    {
        return [
            'user_name.required'=>'vui lòng nhập tên khách hàng',
            'address.required'=>'vui lòng nhập địa chỉ',
            'seri.required'=>'vui lòng nhập số seri',
            'seri.numeric'=>'Trường seri phải là số',
            'seri.unique'=>' Trường số seri đã tồn tại ',
             'brand_id.required'=>'vui lòng chọn hãng sản xuất  ',
            'image.required'=>'vui lòng chọn ảnh',
            'image.image'=>'Trường ảnh không phải 1 ảnh',
        ];
    }
}
