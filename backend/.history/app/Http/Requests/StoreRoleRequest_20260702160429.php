<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    /**
     * آیا کاربر اجازه استفاده از این Request را دارد؟
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * قوانین اعتبارسنجی
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
            ],

            'slug' => [
                'required',
                'string',
                'max:50',
                'unique:roles,slug',
            ],
        ];
    }
}