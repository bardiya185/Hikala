<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:brands,name',
            'slug' => 'required|string|max:255|unique:brands,slug',
            'logo' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام برند الزامی است',
            'name.unique' => 'این نام برند قبلاً ثبت شده است',
            'slug.required' => 'اسلاگ برند الزامی است',
            'slug.unique' => 'این اسلاگ قبلاً ثبت شده است',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'success' => false,
            'message' => 'خطاهای اعتبارسنجی',
            'errors' => $validator->errors()
        ], 422));
    }
}