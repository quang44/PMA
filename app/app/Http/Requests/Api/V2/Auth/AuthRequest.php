<?php

namespace App\Http\Requests\Api\V2\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AuthRequest extends FormRequest
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
            'name'=>'required',
            'phone'=>'required|numeric|unique:users',
            'password'=>'required|min:6',
            'password_confirmation'=>'same:password|min:6',
            'rules_accept'=>'required'
        ];
    }

    function messages()
    {
        return [
            'rules_accept.required'=>'The rule accept field is required'
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