<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'product_variant_id.required' => 'Product variant is required',
            'product_variant_id.exists' => 'Product variant not found',
            'quantity.min' => 'Quantity must be at least 1',
            'quantity.max' => 'Maximum 100 items allowed',
        ];
    }
}