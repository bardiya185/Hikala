<?php

namespace App\Http\Requests\Banner;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // یا چک کن ادمین هست
    }

    public function rules(): array
    {
        return [
            'banner_position_id' => 'required|exists:banner_positions,id',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'alt_text' => 'nullable|string|max:255',
            
            'linkable_type' => [
                'nullable',
                'string',
                Rule::in([Product::class, Category::class, Brand::class]),
                'required_with:linkable_id',
            ],
            'linkable_id' => 'nullable|integer|required_with:linkable_type',
            'custom_url' => 'nullable|url|max:500',
            
            'background_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'text_color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'image.max' => 'حجم تصویر نباید بیشتر از ۲ مگابایت باشد',
            'ends_at.after' => 'تاریخ پایان باید بعد از تاریخ شروع باشد',
            'background_color.regex' => 'کد رنگ باید مثل #FFFFFF باشد',
        ];
    }

    // 🔥 اعتبارسنجی سفارشی: چک کن linkable وجود داره
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->linkable_type && $this->linkable_id) {
                $exists = $this->linkable_type::where('id', $this->linkable_id)->exists();
                if (!$exists) {
                    $validator->errors()->add('linkable_id', 'موجودیت انتخابی یافت نشد');
                }
            }
        });
    }
}