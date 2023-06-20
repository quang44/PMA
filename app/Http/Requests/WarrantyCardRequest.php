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
            'color'=>'required',
            'product'=>'required',
//            'project_photo'=>'required',
            'warranty_code'=>'required|exists:warranty_codes,code|unique:warranty_cards,warranty_code'
        ];
    }

    function messages()
    {
        return [
            'user_name.required'=>'vui lòng nhập tên khách hàng',
            'color.required'=>'vui lòng chọn màu sắc',
            'warranty_code.required'=>'vui lòng nhập code',
            'warranty_code.unique'=>' Trường số seri đã tồn ',
            'warranty_code.exist'=>'Mã bảo hành không tồn tại',
            'product.required'=>'vui lòng chọn hãng sản phẩm  ',
//            'project_photo.required'=>'Vui lòng chọn ảnh công trình',
//            'image.required'=>'vui lòng chọn ảnh',
        ];
    }
}
