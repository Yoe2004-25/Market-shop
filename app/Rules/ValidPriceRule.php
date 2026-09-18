<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidPriceRule implements ValidationRule
{
    protected int|float $min;

    protected int|float $max;

    public function __construct(int|float $min = 0, int|float $max = PHP_FLOAT_MAX)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_numeric($value)) {
            $fail('The :attribute must be a number.');
            return;
        }

        if ($value < $this->min) {
            $fail("The :attribute must be at least {$this->min}.");
        }

        if ($value > $this->max) {
            $fail("The :attribute must not exceed {$this->max}.");
        }
    }
}