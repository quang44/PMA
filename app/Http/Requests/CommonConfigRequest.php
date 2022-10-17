<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommonConfigRequest extends FormRequest
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
            'unit' => 'required',
            'for_referrer' => 'required|numeric',
            'for_activator' => 'required|numeric',
        ];
    }

    function messages()
    {
        return [
            'unit.required' => 'Không để trống đơn vị tiền tệ !',
            'for_referrer.required' => 'Không để trống số point cho người giới thiệu !',
            'for_activator.required' => 'Không để trống số point cho người kích hoạt bảo hiểm !',
            'for_referrer.numeric' => 'Số point cho người giới thiệu phải có định dạng số !',
            'for_activator.numeric' => 'Số point cho người kích hoạt bảo hiểm phải có định dạng số !',
        ];
    }
}
