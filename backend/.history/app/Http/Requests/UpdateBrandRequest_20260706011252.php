<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brandId = $this->route('brand') ? $this->route('brand')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name')->ignore($brandId),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'slug')->ignore($brandId),
            ],
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
}