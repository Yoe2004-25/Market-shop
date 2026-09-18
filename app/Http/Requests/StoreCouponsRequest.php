<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCouponCodeRule; 
use App\Rules\ValidCouponExpiryRule; 
use App\Models\Coupons;
use Illuminate\Validation\Rule; 

class StoreCouponsRequest extends FormRequest
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
            'name'        => ['required', 'string', 'min:3', 'max:225'],
            'code'        => ['required', 'string', new ValidCouponCodeRule()],
            'type'        => ['required', Rule::in([Coupons::TYPE_FIXED, Coupons::TYPE_PERCENTAGE])],
            'value'       => ['required', 'numeric', 'min:0.01'],
            'expire_date' => ['nullable', 'date', new ValidCouponExpiryRule()],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'status'      => ['nullable', Rule::in([Coupons::STATUS_ACTIVE, Coupons::STATUS_INACTIVE])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Please enter the coupon name.',
            'code.required'  => 'Please enter the coupon code.',
            'type.required'  => 'Please choose the discount type.',
            'type.in'        => 'Type must be either fixed or percentage.',
            'value.required' => 'Please enter the discount value.',
            'value.min'      => 'The discount value must be greater than zero.',
        ];
    }
}