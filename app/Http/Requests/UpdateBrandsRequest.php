<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\BrandLogoRule;
use Illuminate\Validation\Rule;
class UpdateBrandsRequest extends FormRequest
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
        $brandId = $this->route('brand')?->id ?? $this->route('id');

        return [
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:225',
                Rule::unique('brands', 'name')->ignore($brandId),
            ],
            'logo' => ['nullable', new BrandLogoRule()],
        ];
    }
}