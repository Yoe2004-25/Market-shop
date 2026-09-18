<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidPriceRule; 
use App\Rules\ValidQuantityRule;

class UpdateOrdersItemsRequest extends FormRequest
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
            'quantity' => ['sometimes', 'integer', new ValidQuantityRule($this->product_id)],
            'price'    => ['sometimes', 'numeric', new ValidPriceRule(1, 1000000)],
            'subtotal' => ['sometimes', 'numeric', 'min:0'],
            'details'  => ['sometimes', 'string', 'min:3', 'max:225'],
        ];
    }

    public function messages() 
    {
        return [
            'price.required'=>'please can you choose the price you want', 
            'quantity.required'=>'please Enter the number you want of Product',
        ];
    }
}