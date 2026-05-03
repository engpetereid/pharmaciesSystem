<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
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
            'pharmacy_id' => ['required', 'integer', 'exists:pharmacies,id'],
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.drug_id' => ['required', 'exists:drugs,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
    public function messages(): array
    {
        return [
            'pharmacy_id.required' => 'pharmacy id is required',
            'pharmacy_id.integer' => 'pharmacy must be an integer',
            'pharmacy_id.exists' => 'pharmacy id does not exist',
            'date.required' => 'Date is required',
            'date.date' => 'Date must be a date',
            'date.date_format' => 'Date format must be Y-m-d',
        ];
    }
}
