<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $attributeId = $this->route('attribute');

        return [

            'name' => ['sometimes', 'string', 'max:255'],

            'slug' => [
                'sometimes',
                'string',
                'max:255',
                'unique:attributes,slug,' . $attributeId
            ],

            'type' => [
                'sometimes',
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