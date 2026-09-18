<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWishlistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => [ 'required', 'integer','exists:products,id', Rule::unique('wishlists', 'product_id')
                    ->where('user_id', $this->user()->id)
                ->whereNull('deleted_at'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required'=>'Please or can be Enter select a product.',
            'product_id.unique'=>'This product is already in your wishlist.',
        ];
    }
}