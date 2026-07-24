<?php

namespace App\Http\Requests;

use App\Enums\ShippingFeatureType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateShippingFeatureRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }



    public function rules(): array
    {
        return [

            'type' => [
                'sometimes',
                new Enum(ShippingFeatureType::class),
            ],


            'title' => [
                'sometimes',
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
        if($this->has('title')){

            $this->merge([
                'title' => trim($this->title),
            ]);

        }
    }

}
