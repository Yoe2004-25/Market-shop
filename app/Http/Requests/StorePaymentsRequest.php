<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentsRequest extends FormRequest
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
            'order_id'       => ['required', 'integer', 'exists:orders,id'],
            'payment_method' => ['required', 'in:cash,visa'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required'       => 'Please select the order.',
            'payment_method.required' => 'Please choose a payment method.',
        ];
    }
}