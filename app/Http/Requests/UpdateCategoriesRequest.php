<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoriesRequest extends FormRequest
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
         $categoryId = $this->route('category')?->id?? $this->route('id');

          return [
            'name'=>'required|string|min:3|max:225',
            'slug'=>'nullable|string|min:3|max:225',[Rule::unique('categories', 'slug')->ignore($categoryId),],
            'description'=>'required|string|min:3|max:1000',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status'=>'nullable|boolean',
        ];
    }

    public  function messages() 
    {
        return [
            'name'=>'please can you Enter the name' ,   
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