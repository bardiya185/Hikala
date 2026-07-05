<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:attributes,slug'],

            'type' => [
                'required',
                'in:text,number,boolean,select,multiselect'
            ],

            'unit' => ['nullable', 'string', 'max:50'],

            'is_filterable' => ['boolean'],

            'is_variant' => ['boolean'],

            'is_required' => ['boolean'],

            'sort_order' => ['integer'],

            'is_active' => ['boolean'],
        ];
    }
}