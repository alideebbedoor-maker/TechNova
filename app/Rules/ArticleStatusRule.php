<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ArticleStatusRule implements ValidationRule
{
    /**
     * التحقق من صحة حالة المقال كـ كلاس مستقل ومتوافق مع لارافيل 12.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowedStatuses = ['draft', 'published', 'archived'];

        if (!in_array($value, $allowedStatuses)) {
            // صياغة رسالة إنجليزية احترافية تظهر بشكل نظيف في الـ Validation Response
            $fail("The fields '{$attribute}' must be one of the following types: " . implode(', ', $allowedStatuses) . ".");
        }
    }
}