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
            'body.min' => 'متن نظر باید حداقل ۱۰ کاراکتر باشد.',
            'body.max' => 'متن نظر نباید بیشتر از ۲۰۰۰ کاراکتر باشد.',
            'rating.min' => 'امتیاز باید بین ۱ تا ۵ باشد.',
            'rating.max' => 'امتیاز باید بین ۱ تا ۵ باشد.',
            'advantages.max' => 'حداکثر ۱۰ نقطه قوت مجاز است.',
            'disadvantages.max' => 'حداکثر ۱۰ نقطه ضعف مجاز است.',
        ];
    }
}