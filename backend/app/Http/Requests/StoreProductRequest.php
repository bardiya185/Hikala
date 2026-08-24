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
            'brand_id.exists' => 'برند انتخاب شده معتبر نیست',
            
            'title.required' => 'عنوان محصول الزامی است',
            'title.string' => 'عنوان باید متن باشد',
            'title.max' => 'عنوان نباید بیشتر از ۲۵۵ کاراکتر باشد',
            
            'slug.required' => 'اسلاگ محصول الزامی است',
            'slug.unique' => 'این اسلاگ قبلاً استفاده شده است',
            'slug.max' => 'اسلاگ نباید بیشتر از ۲۵۵ کاراکتر باشد',
            
            'short_description.max' => 'توضیح مختصر نباید بیشتر از ۵۰۰ کاراکتر باشد',
            
            'status.required' => 'وضعیت محصول الزامی است',
            'status.in' => 'وضعیت باید یکی از draft, active, inactive باشد',
            
            'meta_title.max' => 'عنوان سئو نباید بیشتر از ۲۵۵ کاراکتر باشد',
            'meta_keywords.max' => 'کلمات کلیدی نباید بیشتر از ۲۵۵ کاراکتر باشد',
            'meta_description.max' => 'توضیحات سئو نباید بیشتر از ۵۰۰ کاراکتر باشد',
            
            'sort_order.integer' => 'ترتیب نمایش باید عدد باشد',
            'sort_order.min' => 'ترتیب نمایش نباید کمتر از ۰ باشد',
            
            'is_active.boolean' => 'وضعیت فعال باید true یا false باشد',
            'categories.array' => 'دسته‌بندی‌ها باید به صورت آرایه ارسال شوند',
            'categories.min' => 'حداقل یک دسته‌بندی انتخاب کنید',
            'categories.*.exists' => 'دسته‌بندی انتخاب شده معتبر نیست',
            'variants.array' => 'تنوع‌ها باید به صورت آرایه ارسال شوند',
            'variants.min' => 'حداقل یک تنوع برای محصول ایجاد کنید',

            'variants.*.sku.required' => 'کد انبار (SKU) برای هر تنوع الزامی است',
            'variants.*.sku.unique' => 'کد انبار (SKU) تکراری است',

            'variants.*.price.required' => 'قیمت برای هر تنوع الزامی است',
            'variants.*.price.integer' => 'قیمت باید عدد باشد',
            'variants.*.price.min' => 'قیمت نباید کمتر از ۰ باشد',

            'variants.*.sale_price.integer' => 'قیمت تخفیف باید عدد باشد',
            'variants.*.sale_price.min' => 'قیمت تخفیف نباید کمتر از ۰ باشد',
            'variants.*.sale_price.lt' => 'قیمت تخفیف باید کمتر از قیمت اصلی باشد',

            'variants.*.stock.required' => 'موجودی برای هر تنوع الزامی است',
            'variants.*.stock.integer' => 'موجودی باید عدد باشد',
            'variants.*.stock.min' => 'موجودی نباید کمتر از ۰ باشد',

            'variants.*.weight.integer' => 'وزن باید عدد باشد',
            'variants.*.weight.min' => 'وزن نباید کمتر از ۰ باشد',

            'variants.*.is_active.boolean' => 'وضعیت فعال باید true یا false باشد',
            'variants.*.attributes.array' => 'ویژگی‌ها باید به صورت آرایه ارسال شوند',
            'variants.*.attributes.min' => 'حداقل یک ویژگی برای هر تنوع انتخاب کنید',
            'variants.*.attributes.*.attribute_value_id.required' => 'شناسه مقدار ویژگی الزامی است',
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