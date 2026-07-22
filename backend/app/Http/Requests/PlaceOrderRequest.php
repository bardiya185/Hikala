<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use App\Enums\ShippingMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => ['required', Rule::in(PaymentMethod::values())],
            'shipping_method' => ['nullable', Rule::in(ShippingMethod::values())],
            'customer_note' => 'nullable|string|max:1000',
        ];
    }
}