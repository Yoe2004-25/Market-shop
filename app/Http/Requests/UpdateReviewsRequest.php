<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Reviews;
class UpdateReviewsRequest extends FormRequest
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
            'rating'  => ['sometimes', 'integer', 'min:' . Reviews::MIN_RATING, 'max:' . Reviews::MAX_RATING],
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