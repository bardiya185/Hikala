<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'body' => 'required|string|min:10|max:2000',
            'rating' => 'required|integer|min:1|max:5',
            'advantages' => 'nullable|array|max:10',
            'advantages.*' => 'string|max:255',
            'disadvantages' => 'nullable|array|max:10',
            'disadvantages.*' => 'string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Review text is required.',
            'body.min' => 'Review text must be at least 10 characters.',
            'body.max' => 'Review text may not be greater than 2000 characters.',

            'rating.required' => 'Rating is required.',
            'rating.integer' => 'Rating must be a valid number.',
            'rating.min' => 'Rating must be between 1 and 5.',
            'rating.max' => 'Rating must be between 1 and 5.',

            'advantages.array' => 'Advantages must be an array.',
            'advantages.max' => 'You may add up to 10 advantages only.',
            'advantages.*.string' => 'Each advantage must be a valid text.',
            'advantages.*.max' => 'Each advantage may not be greater than 255 characters.',

            'disadvantages.array' => 'Disadvantages must be an array.',
            'disadvantages.max' => 'You may add up to 10 disadvantages only.',
            'disadvantages.*.string' => 'Each disadvantage must be a valid text.',
            'disadvantages.*.max' => 'Each disadvantage may not be greater than 255 characters.',
        ];
    }
}