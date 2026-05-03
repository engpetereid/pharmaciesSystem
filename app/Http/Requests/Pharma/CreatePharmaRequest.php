<?php

namespace App\Http\Requests\Pharma;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePharmaRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Pharma name is required.',
            'name.string' => 'Pharma name must be a string.',
            'name.max' => 'Pharma name cannot be longer than 100 characters.',
            'user_id.required' => 'User is required.',
            'user_id.integer' => 'User must be an integer.',
            'user_id.exists' => 'User does not exist.',
        ];
    }
}
