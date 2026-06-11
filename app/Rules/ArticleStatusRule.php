<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ArticleStatusRule implements ValidationRule
{
    
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowedStatuses = ['draft', 'published', 'archived'];

        if (!in_array($value, $allowedStatuses)) {

            $fail("The fields '{$attribute}' must be one of the following types: " . implode(', ', $allowedStatuses) . ".");
        }
    }
}