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

     'values' => ['nullable', 'array'],

'values.*.id' => [
    'nullable',
    'exists:attribute_values,id',
],

'values.*.value' => [
    'required',
    'string',
    'max:255',
],

'values.*.code' => [
    'nullable',
    'string',
    'max:255',
],

'values.*.color_code' => [
    'nullable',
    'regex:/^#([A-Fa-f0-9]{6})$/',
],

'values.*.image' => [
    'nullable',
    'string',
],

'values.*.sort_order' => [
    'nullable',
    'integer',
],

'values.*.is_active' => [
    'nullable',
    'boolean',
],
        ];
    }
}