<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use App\Models\Products;
class ValidQuantityRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function __construct(
        private ?int $productId = null
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_numeric($value) || $value < 1) {
            $fail('The :attribute must be at least 1.');
            return;
        }

        if ($this->productId) {
            $product = Products::find($this->productId);
            if ($product && $value > $product->stock) {
                $fail("The requested quantity exceeds available stock ({$product->stock}).");
            }
        }
    }
    
}