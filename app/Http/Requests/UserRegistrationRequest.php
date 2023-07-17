<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'fristName' => 'required|string|max:20',
            'lastName' => 'required|string|max:20',
            'email' => 'required|email|unique:users|max:50',
            'mobile' => 'required|string|max:20',
            'password' => 'required|string|max:50',
            'otp' => 'integer|max:5',
        ];
    }

    public function messages(): array
    {
        return [
            'firstName.required' => 'First name is required.',
            'firstName.string' => 'First name must be a string.',
            'firstName.max' => 'First name may not exceed :max characters.',
            'lastName.required' => 'Last name is required.',
            'lastName.string' => 'Last name must be a string.',
            'lastName.max' => 'Last name may not exceed :max characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'Email is already taken.',
            'email.max' => 'Email may not exceed :max characters.',
            'mobile.required' => 'Mobile is required.',
            'mobile.string' => 'Mobile must be a string.',
            'mobile.max' => 'Mobile may not exceed :max characters.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'password.max' => 'Password may not exceed :max characters.',
            'otp.required' => 'OTP is required.',
            'otp.integer' => 'OTP must be an integer.',
            'otp.max' => 'OTP may not exceed :max.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'The given data is invalid.',
            'errors' => $validator->errors(),
        ], 200));
    }
}
