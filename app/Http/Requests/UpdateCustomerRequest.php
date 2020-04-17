<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'first_name' => 'required|regex:/^[a-zA-Z]+$/u',
            'last_name' => 'required|regex:/^[a-zA-Z]+$/u',
            'email' => 'required|email|unique:customers',
            'gender' => 'required|in:m,f',
            'country' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'first_name.regex' => 'First name can contain only letters',
            'last_name.required' => 'Last name can contain only letters',
            'last_name.regex' => 'Last name can contain only letters',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'email.unique' => 'Email already exists',
            'gender.required' => 'Gender is required',
            'gender.in' => 'gender can only be (m) or (f)',
            'country.required' => 'Country is required'
        ];
    }
}
