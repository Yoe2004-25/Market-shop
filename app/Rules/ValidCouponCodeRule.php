<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use App\Models\Coupons; 
class ValidCouponCodeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  
     */
    public function __construct( private ?int $ignoreId = null) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || strlen($value) < 4) {
            $fail('The coupon code must be at least 4 characters.');
            return;
        }

        if (!preg_match('/^[A-Z0-9\-_]+$/i', $value)) {
            $fail('The coupon code may only contain letters, numbers, dashes, and underscores.');
            return;
        }

        $query = Coupons::where('code', strtoupper($value));

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail('This coupon code is already in use.');
        }
    }
}