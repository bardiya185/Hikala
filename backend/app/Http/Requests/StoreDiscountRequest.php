<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->name),
        ]);
    }


    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:255',
            ],


            'type' => [
                'required',
                Rule::in([
                    'percent',
                    'fixed',
                ]),
            ],


            'value' => [
                'required',
                'numeric',
                'min:0',
            ],


            'stackable' => [
                'nullable',
                'boolean',
            ],


            'starts_at' => [
                'nullable',
                'date',
            ],


            'ends_at' => [
                'nullable',
                'date',
                'after:starts_at',
            ],


            'quantity_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],


            'priority' => [
                'nullable',
                'integer',
                'min:0',
            ],


            'is_flash_sale' => [
                'nullable',
                'boolean',
            ],


            'is_active' => [
                'nullable',
                'boolean',
            ],


            // Discountable

            'discountable_type' => [
                'required',
                Rule::in([
                    'product',
                    'variant',
                    'category',
                    'brand',
                ]),
            ],


            'discountable_ids' => [
                'required',
                'array',
                'min:1',
            ],


            'discountable_ids.*' => [
                'integer',
            ],
        ];
    }


    public function messages(): array
    {
        return [

            'name.required' => 'Discount name is required.',

            'type.in' => 'Discount type must be percent or fixed.',

            'value.min' => 'Discount value must be greater than zero.',

            'ends_at.after' => 'End date must be after start date.',

            'discountable_ids.required' => 'At least one target is required.',

        ];
    }
}
