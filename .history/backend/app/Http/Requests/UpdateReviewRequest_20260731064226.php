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
}