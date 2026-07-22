<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_id' => 'required|exists:discounts,id',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'This coupon code already exists',
            'discount_id.exists' => 'Selected discount not found',
        ];
    }
}