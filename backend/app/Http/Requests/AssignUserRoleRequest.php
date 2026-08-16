<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role' => [
                'required',
                'string',
                'exists:roles,name',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'role.required' => 'Role name is required.',
            'role.exists' => 'The specified role does not exist.',
        ];
    }
}