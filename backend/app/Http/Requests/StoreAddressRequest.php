<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'title' => ['required','string','max:100'],
    
            'receiver_name' => ['required','string','max:255'],
    
            'receiver_mobile' => ['required','digits:11'],
    
            'province_id' => ['required','exists:provinces,id'],
    
            'city_id' => ['required','exists:cities,id'],
    
            'address' => ['required','string'],
    
            'building_number' => ['nullable','string','max:20'],
    
            'unit' => ['nullable','string','max:20'],
    
            'postal_code' => ['nullable','digits:10'],
    
            'latitude' => ['nullable','numeric'],
    
            'longitude' => ['nullable','numeric'],
    
            'is_default' => ['boolean'],
    
        ];
    }
}
