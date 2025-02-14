<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    /**
     * Validate the attribute.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        // '/^\+?[0-9\s\-()]{7,20}$/'
        if (!preg_match('/^\+?[0-9]{10,13}$/', $value)) {
            $fail('The '.$attribute.' is not a valid phone number.');
        }
    }
}