<?php

namespace App\Http\Requests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or check that the user is an admin
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
            'image.max' => 'The image size must not exceed 2 MB.',
            'ends_at.after' => 'The end date must be after the start date.',
            'background_color.regex' => 'The color code must be in the format #FFFFFF.',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->linkable_type && $this->linkable_id) {
                $exists = $this->linkable_type::where('id', $this->linkable_id)->exists();
                if (!$exists) {
                    $validator->errors()->add('linkable_id', 'The selected entity was not found.');
                }
            }
        });
    }
}