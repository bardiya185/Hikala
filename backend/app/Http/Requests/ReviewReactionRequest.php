<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewReactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:like,dislike',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Reaction type is required.',
            'type.in' => 'Reaction type must be like or dislike.',
        ];
    }
}