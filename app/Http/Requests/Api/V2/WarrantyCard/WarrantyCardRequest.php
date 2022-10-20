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
          $qr_code_image='sometimes';
           if ($this->request->get('qr_code_image') !=null) {
               $qr_code_image   = 'required|mimes:jpeg,png,jpg,svg,gif|max:2048';
        }
//        |regex:/^[a-zA-Z_ ]*$/
        return [
            'user_name'=>'required|max:255|unique:warranty_cards',
            'address'=>'required',
            'seri'=>'required|numeric|regex:/^[0-9]*$/u',
            'brand'=>'required',
//            'seri_image'=>'required|mimes:jpeg,png,jpg,svg,gif|max:2048',
//            'qr_code_image'=>$qr_code_image,
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
