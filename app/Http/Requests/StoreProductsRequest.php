<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductsRequest extends FormRequest
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
        $productId = $this->route('products')??NULL ; 
        
      return [
            'categery_id'=>'required|ip', 
            'brand_id'=>'required|ip',
            'name'=>'required|string|min:3|max:225', 
            'slug'=>'required|string|min:3|max:225|unique:products,slug',
            'description'=>'required|string|min:3|max:225', 
            'price'=>'required|numeric',
            'discount'=>'nullable|numeric', 
            'stock'=>'nullable|numeric',
            'sku'=>'nullable|numeric',
            'image.*'=>'imaege|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'status'=>'required|boolean', 
        ];
    }



    public function messages() 
    {
        return [
            'name.required'=>'please can you Enter the name of you want', 
            'description.required'=>'please can you enter the more Details', 
            'price.required'=>'please can you Enter the price', 
        ];
    }
}