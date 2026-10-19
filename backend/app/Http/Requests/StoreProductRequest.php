<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
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
                'unique:products,slug',
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
                'min:1',
            ],

            'variants.*.sku' => [
                'required',
                'string',
                'max:255',
                'unique:product_variants,sku',
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
                'min:1',
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
            'brand_id.exists' => 'The selected brand is not valid.',
            
            'title.required' => 'The product title field is required.',
            'title.string' => 'The title must be text.',
            'title.max' => 'The title must not be greater than 255 characters.',
            
            'slug.required' => 'The product slug field is required.',
            'slug.unique' => 'This slug has already been used.',
            'slug.max' => 'The slug must not be greater than 255 characters.',
            
            'short_description.max' => 'The short description must not be greater than 500 characters.',
            
            'status.required' => 'The product status field is required.',
            'status.in' => 'The status must be one of draft, active or inactive.',
            
            'meta_title.max' => 'The SEO title must not be greater than 255 characters.',
            'meta_keywords.max' => 'The meta keywords must not be greater than 255 characters.',
            'meta_description.max' => 'The SEO description must not be greater than 500 characters.',
            
            'sort_order.integer' => 'The sort order must be a number.',
            'sort_order.min' => 'The sort order must not be less than 0.',
            
            'is_active.boolean' => 'The active status must be true or false.',
            'categories.array' => 'The categories must be sent as an array.',
            'categories.min' => 'Please select at least one category.',
            'categories.*.exists' => 'The selected category is not valid.',
            'variants.array' => 'The variants must be sent as an array.',
            'variants.min' => 'Please create at least one variant for the product.',

            'variants.*.sku.required' => 'The SKU is required for each variant.',
            'variants.*.sku.unique' => 'This SKU is already taken.',

            'variants.*.price.required' => 'The price is required for each variant.',
            'variants.*.price.integer' => 'The price must be a number.',
            'variants.*.price.min' => 'The price must not be less than 0.',

            'variants.*.sale_price.integer' => 'The discount price must be a number.',
            'variants.*.sale_price.min' => 'The discount price must not be less than 0.',
            'variants.*.sale_price.lt' => 'The discount price must be lower than the original price.',

            'variants.*.stock.required' => 'The stock is required for each variant.',
            'variants.*.stock.integer' => 'The stock must be a number.',
            'variants.*.stock.min' => 'The stock must not be less than 0.',

            'variants.*.weight.integer' => 'The weight must be a number.',
            'variants.*.weight.min' => 'The weight must not be less than 0.',

            'variants.*.is_active.boolean' => 'The active status must be true or false.',
            'variants.*.attributes.array' => 'The attributes must be sent as an array.',
            'variants.*.attributes.min' => 'Please select at least one attribute for each variant.',
            'variants.*.attributes.*.attribute_value_id.required' => 'The attribute value ID field is required.',
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