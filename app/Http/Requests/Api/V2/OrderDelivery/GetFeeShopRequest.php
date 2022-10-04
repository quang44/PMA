<?php

namespace App\Http\Requests\Api\V2\OrderDelivery;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class GetFeeShopRequest extends FormRequest
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
            'collect_amount' => 'integer',
            'product_price' => 'integer',
            'weight' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'length' => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => trans('Vui lòng nhập tên gói hàng'),
            'product_price.required' => trans('Vui lòng nhập giá trị gói hàng'),
            'product_price.integer' => trans('Giá trị gói hàng nhập không đúng'),
            'product_number.required' => trans('Vui lòng nhập số lượng gói hàng'),
            'product_number.integer' => trans('Số lượng gói hàng nhập không đúng'),
            'collect_amount.required' => trans('Vui lòng nhập số tiền thu hộ'),
            'collect_amount.integer' => trans('Số tiền thu hộ nhập không đúng'),
            /*'user_id.required' => 'Vui lòng chọn khách hàng',
            'user_id.integer' => 'Vui lòng chọn khách hàng',
            'start_time.required' => 'Vui lòng chọn thời gian bắt đầu',
            'start_time.date' => 'Vui lòng chọn thời gian bắt đầu',
            'start_time.date_format' => 'Vui lòng chọn thời gian bắt đầu',*/
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $json = [
            'result' => false,
            'message' => $validator->errors()->first()
        ];
        $response = response( $json, 200 );
        throw (new ValidationException($validator, $response))->status(200);
    }
}
