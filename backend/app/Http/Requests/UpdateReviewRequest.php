<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => 'sometimes|required|string|min:10|max:2000',
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'advantages' => 'nullable|array|max:10',
            'advantages.*' => 'string|max:255',
            'disadvantages' => 'nullable|array|max:10',
            'disadvantages.*' => 'string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'body.min' => 'Review text must be at least 10 characters.',
            'body.max' => 'Review text may not be greater than 2000 characters.',
            'rating.min' => 'Rating must be between 1 and 5.',
            'rating.max' => 'Rating must be between 1 and 5.',
        ];
    }
}