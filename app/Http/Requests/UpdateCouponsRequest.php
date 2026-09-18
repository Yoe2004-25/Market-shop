<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCouponCodeRule; 
use App\Rules\ValidCouponExpiryRule ;
use Illuminate\Validation\Rule; 
use App\Models\Coupons;
class UpdateCouponsRequest extends FormRequest
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
        // الحصول على id من الـ route
        $couponId = $this->route('coupon')?->id ?? $this->route('id');

        return [
            'name'        => ['sometimes', 'string', 'min:3', 'max:225'],
            'code'        => ['sometimes', 'string', new ValidCouponCodeRule($couponId)],
            'type'        => ['sometimes', Rule::in([Coupons::TYPE_FIXED, Coupons::TYPE_PERCENTAGE])],
            'value'       => ['sometimes', 'numeric', 'min:0.01'],
            'expire_date' => ['nullable', 'date', new ValidCouponExpiryRule()],
            'usage_limit' => ['sometimes', 'integer', 'min:1'],
            'status'      => ['sometimes', Rule::in([Coupons::STATUS_ACTIVE, Coupons::STATUS_INACTIVE])],
        ];
    }
}