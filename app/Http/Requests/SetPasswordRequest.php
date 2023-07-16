<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SetPasswordRequest extends FormRequest
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
            'password' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'password.max' => 'Password may not exceed :max characters.', 
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'The given data is invalid.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
