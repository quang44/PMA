<?php

namespace App\Http\Requests\Api\V2\OrderDelivery;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateAddressRequest extends FormRequest
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
            'address_type' => 'required|integer|in:1,2,3',
            'province' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'address' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
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
