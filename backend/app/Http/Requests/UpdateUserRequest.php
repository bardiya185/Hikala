<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

/**
 * Validates the profile update sent to PATCH /api/user.
 *
 * The users table of this project stores the phone number in the `mobile`
 * column and has no `email` column, so the rules are built from the columns
 * that actually exist. `phone` is accepted as an alias of `mobile`, and an
 * `email` rule is attached automatically as soon as the column is added.
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Normalise the payload before validation:
     * - `phone` is merged into `mobile`
     * - Persian / Arabic digits and separators are converted to a plain number
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('phone') && !$this->has('mobile')) {
            $this->merge(['mobile' => $this->input('phone')]);
        }

        if ($this->has('mobile')) {
            $this->merge(['mobile' => $this->toLatinDigits($this->input('mobile'))]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userId = $this->user()->id;

        $rules = [
            'name' => ['sometimes', 'required', 'string', 'min:2', 'max:255'],
        ];

        if (Schema::hasColumn('users', 'mobile')) {
            $rules['mobile'] = [
                'sometimes',
                'required',
                'string',
                'regex:/^09[0-9]{9}$/',
                Rule::unique('users', 'mobile')->ignore($userId),
            ];
        }

        if (Schema::hasColumn('users', 'email')) {
            $rules['email'] = [
                'sometimes',
                'required',
                'string',
                'email:rfc',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام الزامی است',
            'name.string' => 'نام باید متن باشد',
            'name.min' => 'نام باید حداقل :count نویسه باشد',
            'name.max' => 'نام نباید بیشتر از :count نویسه باشد',
            'mobile.required' => 'شماره موبایل الزامی است',
            'mobile.regex' => 'فرمت شماره موبایل نامعتبر است (مثال: ۰۹۱۲۳۴۵۶۷۸۹)',
            'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است',
            'email.required' => 'ایمیل الزامی است',
            'email.email' => 'فرمت ایمیل نامعتبر است',
            'email.max' => 'ایمیل نباید بیشتر از :count نویسه باشد',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'نام',
            'mobile' => 'شماره موبایل',
            'email' => 'ایمیل',
        ];
    }

    /**
     * Convert Persian (۰-۹) and Arabic (٠-٩) digits to Latin digits
     * and strip spaces, dashes and parentheses.
     */
    private function toLatinDigits($value): string
    {
        $value = (string) $value;

        $value = str_replace(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $value
        );

        $value = str_replace(
            ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $value
        );

        return preg_replace('/[^0-9]/', '', $value) ?? '';
    }
}
