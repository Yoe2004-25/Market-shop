<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoriesRequest extends FormRequest
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
            'name'=>'required|string|min:3|max:225',
            'slug'=>'nullable|string|min:3|max:225,unique:categories,slug',
            'description'=>'required|string|min:3|max:1000',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status'=>'nullable|boolean',
        ];
    }   


     protected function prepareForValidation(): void
    {
        if ($this->has('name') && !$this->has('slug')) {
            $this->merge([
                'slug' => str($this->name)->slug()
            ]);
        }
    }
}