<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotDisposableEmail implements ValidationRule
{
    /**
     * List of disposable email domains.
     * In production, use a library or API service for a more comprehensive list.
     */
    protected $disposableDomains = [
        'yopmail.com', 'temp-mail.org', 'tempmail.com', '10minutemail.com',
        'guerrillamail.com', 'sharklasers.com', 'mailinator.com',
        'dispostable.com', 'maildrop.cc', 'getairmail.com',
        'throwawaymail.com', 'tempr.email', 'mailnesia.com'
    ];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            return;
        }

        $emailParts = explode('@', $value);
        if (count($emailParts) !== 2) {
            return;
        }

        $domain = strtolower($emailParts[1]);

        if (in_array($domain, $this->disposableDomains)) {
            $fail('The :attribute domain is not allowed. Please use a permanent email address.');
        }
    }
}
