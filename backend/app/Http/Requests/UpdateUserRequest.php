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
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be text.',
            'name.min' => 'The name must be at least :count characters.',
            'name.max' => 'The name must not be greater than :count characters.',
            'mobile.required' => 'The mobile number field is required.',
            'mobile.regex' => 'Invalid mobile number format (e.g. 09123456789).',
            'mobile.unique' => 'This mobile number is already taken.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email format is invalid.',
            'email.max' => 'The email must not be greater than :count characters.',
            'email.unique' => 'This email is already taken.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'name',
            'mobile' => 'mobile number',
            'email' => 'email',
        ];
    }

    /**
     * Convert Persian (U+06F0-U+06F9) and Arabic-Indic (U+0660-U+0669) digits
     * to Latin digits and strip spaces, dashes and parentheses.
     */
    private function toLatinDigits($value): string
    {
        $value = (string) $value;

        $value = str_replace(
            ["\u{06F0}", "\u{06F1}", "\u{06F2}", "\u{06F3}", "\u{06F4}", "\u{06F5}", "\u{06F6}", "\u{06F7}", "\u{06F8}", "\u{06F9}"],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $value
        );

        $value = str_replace(
            ["\u{0660}", "\u{0661}", "\u{0662}", "\u{0663}", "\u{0664}", "\u{0665}", "\u{0666}", "\u{0667}", "\u{0668}", "\u{0669}"],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $value
        );

        return preg_replace('/[^0-9]/', '', $value) ?? '';
    }
}
