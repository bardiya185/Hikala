<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category') ? $this->route('category')->id : null;

        return [
            'parent_id' => 'nullable|exists:categories,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($categoryId),
            ],
            'icon_key' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام دسته‌بندی الزامی است',
            'name.unique' => 'این نام دسته‌بندی قبلاً ثبت شده است',
            'slug.required' => 'اسلاگ دسته‌بندی الزامی است',
            'slug.unique' => 'این اسلاگ قبلاً ثبت شده است',
            'parent_id.exists' => 'دسته‌بندی والد انتخاب شده معتبر نیست',
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