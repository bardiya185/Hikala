<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {

            $this->merge([
                'name' => trim((string) $this->name),
            ]);

        }
    }


    public function rules(): array
    {
        return [

            'name' => [
                'sometimes',
                'string',
                'max:255',
            ],


            'type' => [
                'sometimes',
                Rule::in([
                    'percent',
                    'fixed',
                ]),
            ],


            'value' => [
                'sometimes',
                'numeric',
                'min:0',
            ],


            'stackable' => [
                'sometimes',
                'boolean',
            ],


            'starts_at' => [
                'sometimes',
                'nullable',
                'date',
            ],


            'ends_at' => [
                'sometimes',
                'nullable',
                'date',
                'after:starts_at',
            ],


            'quantity_limit' => [
                'sometimes',
                'nullable',
                'integer',
                'min:1',
            ],


            'priority' => [
                'sometimes',
                'integer',
                'min:0',
            ],


            'is_flash_sale' => [
                'sometimes',
                'boolean',
            ],


            'is_active' => [
                'sometimes',
                'boolean',
            ],


            // Discountable relations

            'discountable_type' => [
                'sometimes',
                Rule::in([
                    'product',
                    'variant',
                    'category',
                    'brand',
                ]),
            ],


            'discountable_ids' => [
                'sometimes',
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

            'type.in' => 'Discount type must be percent or fixed.',

            'value.min' => 'Discount value cannot be negative.',

            'ends_at.after' => 'End date must be after start date.',

            'discountable_ids.array' => 'Discount targets must be an array.',

        ];
    }
}
