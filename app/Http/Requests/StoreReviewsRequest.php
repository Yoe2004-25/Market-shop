<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Reviews;
class StoreReviewsRequest extends FormRequest
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
            'product_id' => 
            [
            'required', 'integer', 'exists:products,id',
                Rule::unique('reviews', 'product_id')
            ->where('user_id', $this->user()->id),
            ],
            'rating'  => ['required', 'integer', 'min:' . Reviews::MIN_RATING, 'max:' . Reviews::MAX_RATING],
            'comment' => ['nullable', 'string', 'min:3', 'max:2000'],
        ];
    }


    public function messages() 
    {
        return 
        [
            'comment.required'=>'please can you write a comment' , 
        ];
    }
}