<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mobile' => ['required', 'regex:/^09[0-9]{9}$/'],
            'code' => 'required|digits:6',
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.required' => 'The mobile number field is required.',
            'mobile.regex' => 'Invalid mobile number format (e.g. 09123456789).',
            'code.required' => 'The verification code field is required.',
            'code.digits' => 'The verification code must be 6 digits.',
        ];
    }
}