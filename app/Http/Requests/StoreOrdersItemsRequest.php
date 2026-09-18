<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidPriceRule; 
use App\Rules\ValidQuantityRule;
class StoreOrdersItemsRequest extends FormRequest
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
            'order_id'   => ['required', 'integer', 'exists:orders,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['required', 'integer', new ValidQuantityRule($this->product_id)],
            'price'      => ['required', 'numeric', new ValidPriceRule(1, 1000000)],
            'subtotal'   => ['nullable', 'numeric', 'min:0'],
            'details'    => ['required', 'string', 'min:3', 'max:225'],
        ];
    }


    public function messages(): array
    {
        return [
            'order_id.required'   => 'Please select the order.',
            'order_id.exists'     => 'The selected order does not exist.',
            'product_id.required' => 'Please select the product.',
            'product_id.exists'   => 'The selected product does not exist.',
            'price.required'      => 'Please choose the price you want.',
            'quantity.required'   => 'Please enter the quantity you want.',
            'details.required'    => 'Please enter the details.',
        ];
    }
}