<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;


class SaveWarehouseRequest extends FormRequest
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
            'quantity' => 'required|integer|min:1',
            'pharmacy_id' => 'required|integer|exists:pharmacies,id',
            'drug_id' => 'required|integer|exists:drugs,id',
            'minimum_quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be an integer',
            'quantity.min' => 'Quantity must be at least 1',
            'pharmacy_id.required' => 'Pharmacy is required',
            'pharmacy_id.integer' => 'Pharmacy must be an integer',
            'pharmacy_id.exists' => 'Pharmacy does not exist',
            'drug_id.required' => 'drug is required',
            'drug_id.exists' => 'drug does not exist',
            'minimum_quantity.required' => 'Minimum quantity is required',
            'minimum_quantity.integer' => 'Minimum quantity must be an integer',
            'minimum_quantity.min' => 'Minimum quantity must be at least 1',
        ];
    }
}
