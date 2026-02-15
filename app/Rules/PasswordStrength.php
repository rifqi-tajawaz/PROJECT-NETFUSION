<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PasswordStrength implements ValidationRule
{
    protected int $minLength;
    protected bool $requireUppercase;
    protected bool $requireLowercase;
    protected bool $requireNumbers;
    protected bool $requireSpecialChars;
    protected bool $checkBreaches;

    public function __construct(
        int $minLength = 8,
        bool $requireUppercase = true,
        bool $requireLowercase = true,
        bool $requireNumbers = true,
        bool $requireSpecialChars = true,
        bool $checkBreaches = true
    ) {
        $this->minLength = $minLength;
        $this->requireUppercase = $requireUppercase;
        $this->requireLowercase = $requireLowercase;
        $this->requireNumbers = $requireNumbers;
        $this->requireSpecialChars = $requireSpecialChars;
        $this->checkBreaches = $checkBreaches;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $password = (string) $value;

        // Check minimum length
        if (strlen($password) < $this->minLength) {
            $fail("Password harus memiliki minimal {$this->minLength} karakter.");
            return;
        }

        // Check for uppercase letters
        if ($this->requireUppercase && !preg_match('/[A-Z]/', $password)) {
            $fail("Password harus mengandung setidaknya satu huruf besar.");
            return;
        }

        // Check for lowercase letters
        if ($this->requireLowercase && !preg_match('/[a-z]/', $password)) {
            $fail("Password harus mengandung setidaknya satu huruf kecil.");
            return;
        }

        // Check for numbers
        if ($this->requireNumbers && !preg_match('/[0-9]/', $password)) {
            $fail("Password harus mengandung setidaknya satu angka.");
            return;
        }

        // Check for special characters
        if ($this->requireSpecialChars && !preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
            $fail("Password harus mengandung setidaknya satu karakter spesial.");
            return;
        }

        // Check for common patterns
        $this->checkCommonPatterns($attribute, $password, $fail);

        // Check for entropy strength
        $this->checkEntropy($attribute, $password, $fail);

        // Check against known breaches
        if ($this->checkBreaches) {
            $this->checkBreachedPasswords($attribute, $password, $fail);
        }
    }

    /**
     * Check for common password patterns.
     */
    protected function checkCommonPatterns(string $attribute, string $password, Closure $fail): void
    {
        $patterns = [
            // Sequential characters
            '/(?:abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz)/i',
            '/(?:123|234|345|456|567|678|789|890)/',

            // Keyboard patterns
            '/(?:qwerty|asdf|zxcv|qaz|wsx|edc|rfv|tgb|yhn|ujm|ik|ol)/i',

            // Repeated characters
            '/(.)\1{2,}/',

            // Common words
            '/(?:password|admin|user|login|welcome|123456|qwerty)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $password)) {
                $fail("Password mengandung pola umum yang mudah ditebak dan tidak cupuk aman.");
                return;
            }
        }

        // Check if password is too similar to common words
        $commonPasswords = [
            'password',
            '123456',
            'password123',
            'admin',
            'letmein',
            'welcome',
            'monkey',
            '1234567890',
            'qwerty',
            'abc123',
            'Password1',
            'password1',
            '123456789',
            'iloveyou'
        ];

        foreach ($commonPasswords as $common) {
            if (stripos($password, $common) !== false) {
                $fail("Password terlalu mirip dengan kata sandi umum.");
                return;
            }
        }
    }

    /**
     * Check password entropy.
     */
    protected function checkEntropy(string $attribute, string $password, Closure $fail): void
    {
        $entropy = $this->calculateEntropy($password);

        if ($entropy < 50) {
            $fail("Password tidak cukup kompleks. Harap gunakan kombinasi karakter yang lebih beragam.");
        }
    }

    /**
     * Calculate password entropy.
     */
    protected function calculateEntropy(string $password): float
    {
        $charSet = 0;

        if (preg_match('/[a-z]/', $password))
            $charSet += 26; // lowercase
        if (preg_match('/[A-Z]/', $password))
            $charSet += 26; // uppercase
        if (preg_match('/[0-9]/', $password))
            $charSet += 10; // numbers
        if (preg_match('/[^a-zA-Z0-9]/', $password))
            $charSet += 32; // special chars

        if ($charSet === 0)
            return 0;

        $entropy = strlen($password) * log($charSet, 2);

        return $entropy;
    }

    /**
     * Check against breached passwords using HaveIBeenPwned API.
     */
    protected function checkBreachedPasswords(string $attribute, string $password, Closure $fail): void
    {
        try {
            $hash = strtoupper(sha1($password));
            $prefix = substr($hash, 0, 5);
            $suffix = substr($hash, 5);

            $response = Http::timeout(5)
                ->withHeaders([
                    'User-Agent' => 'Laravel Password Validator'
                ])
                ->get("https://api.pwnedpasswords.com/range/{$prefix}");

            if ($response->successful()) {
                $hashes = $response->body();

                if (strpos($hashes, $suffix) !== false) {
                    $fail("Password ini telah ditemukan dalam kebocoran data (data breach). Harap gunakan password yang berbeda.");
                    return;
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't fail validation if API is down
            \Log::warning('Failed to check breached passwords: ' . $e->getMessage());
        }
    }

    /**
     * Generate password strength feedback.
     */
    public static function getStrengthFeedback(string $password): array
    {
        $feedback = [];
        $score = 0;

        // Length check
        if (strlen($password) >= 12) {
            $score += 25;
        } elseif (strlen($password) >= 8) {
            $score += 15;
        } else {
            $feedback[] = 'Use at least 8 characters (12+ recommended)';
        }

        // Uppercase check
        if (preg_match('/[A-Z]/', $password)) {
            $score += 15;
        } else {
            $feedback[] = 'Add uppercase letters';
        }

        // Lowercase check
        if (preg_match('/[a-z]/', $password)) {
            $score += 15;
        } else {
            $feedback[] = 'Add lowercase letters';
        }

        // Numbers check
        if (preg_match('/[0-9]/', $password)) {
            $score += 15;
        } else {
            $feedback[] = 'Add numbers';
        }

        // Special characters check
        if (preg_match('/[^a-zA-Z0-9]/', $password)) {
            $score += 20;
        } else {
            $feedback[] = 'Add special characters';
        }

        // Avoid common patterns
        if (!preg_match('/(?:123|abc|qwerty|password|admin)/i', $password)) {
            $score += 10;
        } else {
            $feedback[] = 'Avoid common patterns';
        }

        // Determine strength level
        $strength = 'weak';
        $color = 'danger';

        if ($score >= 80) {
            $strength = 'very strong';
            $color = 'success';
        } elseif ($score >= 60) {
            $strength = 'strong';
            $color = 'info';
        } elseif ($score >= 40) {
            $strength = 'fair';
            $color = 'warning';
        }

        return [
            'score' => min(100, $score),
            'strength' => $strength,
            'color' => $color,
            'feedback' => $feedback
        ];
    }
}
