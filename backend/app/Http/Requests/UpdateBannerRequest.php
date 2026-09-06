<?php

namespace App\Http\Requests;

class UpdateBannerRequest extends StoreBannerRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        $rules['banner_position_id'] = 'sometimes|exists:banner_positions,id';
        
        return $rules;
    }
}