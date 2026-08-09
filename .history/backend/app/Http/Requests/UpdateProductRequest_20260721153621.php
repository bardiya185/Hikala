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
            // ===== اطلاعات اصلی محصول =====
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

            // ===== دسته‌بندی‌ها =====
            'categories' => [
                'nullable',
                'array',
                'min:1',
            ],

            'categories.*' => [
                'exists:categories,id',
            ],

            // ===== تنوع‌ها (Variants) =====
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

            'variants.*.weight' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'variants.*.is_active' => [
                'nullable',
                'boolean',
            ],

            // ===== ویژگی‌های تنوع =====
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
            'title.required' => 'عنوان محصول الزامی است',
            'title.string' => 'عنوان باید متن باشد',
            'title.max' => 'عنوان نباید بیشتر از ۲۵۵ کاراکتر باشد',
            
            'slug.required' => 'اسلاگ محصول الزامی است',
            'slug.unique' => 'این اسلاگ قبلاً استفاده شده است',
            'slug.max' => 'اسلاگ نباید بیشتر از ۲۵۵ کاراکتر باشد',
            
            'status.required' => 'وضعیت محصول الزامی است',
            'status.in' => 'وضعیت باید یکی از draft, active, inactive باشد',
            
            'categories.min' => 'حداقل یک دسته‌بندی انتخاب کنید',
            'categories.*.exists' => 'دسته‌بندی انتخاب شده معتبر نیست',

            'variants.*.sku.required' => 'کد انبار (SKU) برای هر تنوع الزامی است',
            'variants.*.sku.unique' => 'کد انبار (SKU) تکراری است',
            'variants.*.price.required' => 'قیمت برای هر تنوع الزامی است',
            'variants.*.price.min' => 'قیمت نباید کمتر از ۰ باشد',
            'variants.*.sale_price.lt' => 'قیمت تخفیف باید کمتر از قیمت اصلی باشد',
            'variants.*.stock.required' => 'موجودی برای هر تنوع الزامی است',
            'variants.*.stock.min' => 'موجودی نباید کمتر از ۰ باشد',
            'variants.*.attributes.*.attribute_value_id.exists' => 'مقدار ویژگی انتخاب شده معتبر نیست',
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