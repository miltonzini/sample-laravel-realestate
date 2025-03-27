<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoSQLInjection implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $sqlPattern = '/\b(SELECT|INSERT|UPDATE|DELETE|DROP|ALTER)\b/i';
        
        if (preg_match($sqlPattern, $value)) {
            $fail('The :attribute field contains reserved SQL patterns.');
        }
    }
}
