<?php

namespace App\Http\Requests\Drug;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateDrugRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:1'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.string' => 'name must be string',
            'name.required' => 'name is required',
            'price.numeric' => 'price must be numeric',
            'price.required' => 'price is required',
            'category_id.integer' => 'category_id must be integer',
            'category_id.required' => 'category_id is required',
        ];
    }
}
