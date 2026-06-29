<?php

namespace App\Http\Requests\Notification;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;


class SaveNotificationRequest extends FormRequest
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
            'pharmacy_id' => 'required|integer|exists:pharmacies,id',
            'drug_id' => 'required|integer|exists:drugs,id',
            'message' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'pharmacy_id.required' => 'Pharmacy id is required',
            'pharmacy_id.integer' => 'Pharmacy id is not an integer',
            'pharmacy_id.exists' => 'Pharmacy id does not exist',
            'drug_id.required' => 'drug id is required',
            'drug_id.integer' => 'drug id is not an integer',
            'drug_id.exists' => 'drug id does not exist',
            'message.required' => 'message is required',
            'message.string' => 'message is not string',
        ];
    }
}
