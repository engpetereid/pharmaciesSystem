<?php

namespace App\Http\Requests\Pharma;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDrugsRequest extends FormRequest
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
            'pharmacy_id'        => ['required', 'integer', 'exists:pharmacies,id'],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.drug_id'    => ['required', 'integer', 'exists:drugs,id'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'pharmacy_id.required'        => 'Pharmacy is required.',
            'pharmacy_id.exists'          => 'Selected pharmacy does not exist.',
            'items.required'              => 'At least one item is required.',
            'items.min'                   => 'At least one item is required.',
            'items.*.drug_id.required'    => 'Drug is required for each item.',
            'items.*.drug_id.exists'      => 'One or more selected drugs do not exist.',
            'items.*.quantity.required'   => 'Quantity is required for each item.',
            'items.*.quantity.integer'    => 'Quantity must be a whole number.',
            'items.*.quantity.min'        => 'Quantity must be at least 1.',
        ];
    }
}
