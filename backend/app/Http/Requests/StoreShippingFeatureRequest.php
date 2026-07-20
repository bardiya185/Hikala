<?php

namespace App\Http\Requests;

use App\Enums\ShippingFeatureType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreShippingFeatureRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [

            'type' => [
                'required',
                new Enum(ShippingFeatureType::class),
            ],


            'title' => [
                'required',
                'string',
                'max:255',
                'min:3',
            ],


            'description' => [
                'nullable',
                'string',
            ],


            'is_active' => [
                'boolean',
            ],

        ];
    }



    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim($this->title),
        ]);
    }

}
