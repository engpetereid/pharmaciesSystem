<?php

namespace App\Http\Requests\Order;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;


class SaveOrderRequest extends FormRequest
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
            'drug_id'=>'required|exists:drugs,id',
            'pharmacy_id'=>'required|exists:pharmacies,id',
            'quantity'=>'required|numeric|min:1',
            'accepted'=>'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'drug_id.required' => 'drug_id is required',
            'pharmacy_id.required' => 'pharmacy_id is required',
            'quantity.required' => 'quantity is required',
            'accepted.required' => 'accepted is required',
            'accepted.in' => 'accepted must be 0 or 1',
        ];
    }
}
