<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Fix Issue 7: removed dead message keys (name, role, email.unique) that
 * were copied from CreateUserRequest but don't correspond to any rule here.
 */
class LoginUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|string|email|max:100',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'The email field is required.',
            'email.email'       => 'The email must be a valid email address.',
            'email.max'         => 'The email may not exceed 100 characters.',
            'password.required' => 'The password field is required.',
            'password.min'      => 'The password must be at least 8 characters.',
        ];
    }
}
