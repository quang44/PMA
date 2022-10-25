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
            'seri'=>'required|numeric|regex:/^[0-9]*$/u',
            'brand'=>'required',
            'image'=>'required',
        ];
    }

    function messages()
    {
        return [];
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
