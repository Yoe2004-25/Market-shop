<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrdersRequest extends FormRequest
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
            'name'=>'required|string|max:3|max:225',
            'status'=>'required|enum|in:success,failed' , 
            'payment_status'=>'required|enum|in:pending,success,failed,refunded', 
            'total'=>'required|numeric|min:0', 
            'shipping_cost'=>'sometimes|numeric|min:0',
            'tax'=>'somtimes|numeric|min:0',
            'grand_total'=>'required|numeric|min:0',
        ];
    }
}