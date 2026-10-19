<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product') ? $this->route('product')->id : null;

        return [
            'brand_id' => [
                'nullable',
                'exists:brands,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($productId),
            ],

            'short_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                Rule::in(['draft', 'active', 'inactive']),
            ],

            'meta_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meta_keywords' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meta_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
            'categories' => [
                'nullable',
                'array',
                'min:1',
            ],

            'categories.*' => [
                'exists:categories,id',
            ],
            'variants' => [
                'nullable',
                'array',
            ],

            'variants.*.id' => [
                'nullable',
                'exists:product_variants,id',
            ],

            'variants.*.sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_variants', 'sku')->ignore($this->input('variants.*.id')),
            ],

            'variants.*.barcode' => [
                'nullable',
                'string',
                'max:255',
            ],

            'variants.*.base_price' => [
                'required',
                'integer',
                'min:0',
            ],

            'variants.*.stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'variants.*.max_order_quantity' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],

            'variants.*.weight' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'variants.*.is_active' => [
                'nullable',
                'boolean',
            ],
            'variants.*.attributes' => [
                'nullable',
                'array',
            ],

            'variants.*.attributes.*.attribute_value_id' => [
                'required',
                'exists:attribute_values,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The product title field is required.',
            'title.string' => 'The title must be text.',
            'title.max' => 'The title must not be greater than 255 characters.',
            
            'slug.required' => 'The product slug field is required.',
            'slug.unique' => 'This slug has already been used.',
            'slug.max' => 'The slug must not be greater than 255 characters.',
            
            'status.required' => 'The product status field is required.',
            'status.in' => 'The status must be one of draft, active or inactive.',
            
            'categories.min' => 'Please select at least one category.',
            'categories.*.exists' => 'The selected category is not valid.',

            'variants.*.sku.required' => 'The SKU is required for each variant.',
            'variants.*.sku.unique' => 'This SKU is already taken.',
            'variants.*.price.required' => 'The price is required for each variant.',
            'variants.*.price.min' => 'The price must not be less than 0.',
            'variants.*.sale_price.lt' => 'The discount price must be lower than the original price.',
            'variants.*.stock.required' => 'The stock is required for each variant.',
            'variants.*.stock.min' => 'The stock must not be less than 0.',
            'variants.*.attributes.*.attribute_value_id.exists' => 'The selected attribute value is not valid.',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}