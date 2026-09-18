<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BrandLogoRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value) {
            return; // nullable
        }

        if (!$value instanceof \Illuminate\Http\UploadedFile) {
            $fail('The logo must be a valid uploaded file.');
            return;
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/svg+xml'];

        if (!in_array($value->getMimeType(), $allowedMimes, true)) {
            $fail('The logo must be a JPG, PNG, WEBP, or SVG image.');
        }

        if ($value->getSize() > 2 * 1024 * 1024) {
            $fail('The logo size must not exceed 2MB.');
        }
    }
}