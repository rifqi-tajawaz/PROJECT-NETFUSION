<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // If reCAPTCHA is disabled in config, skip validation
        if (!config('recaptcha.enabled', false)) {
            return;
        }

        // If no site key configured, skip validation
        if (!env('RECAPTCHA_SECRET_KEY')) {
            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(10) // 10 second timeout
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => env('RECAPTCHA_SECRET_KEY'),
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

            if (!$response->successful()) {
                // If Google API is down, allow the request to proceed
                // Log this for monitoring purposes
                \Log::warning('reCAPTCHA API unavailable', [
                    'status' => $response->status(),
                    'ip' => request()->ip()
                ]);
                return;
            }

            $data = $response->json();

            if (!$data['success']) {
                $errorCodes = $data['error-codes'] ?? [];
                \Log::warning('reCAPTCHA validation failed', ['errors' => $errorCodes]);
                $fail('Verification failed. Please try again.');
                return;
            }

            // v3 Score Check (0.0 to 1.0)
            // 1.0 is very likely a human, 0.0 is very likely a bot
            if (isset($data['score']) && $data['score'] < 0.5) {
                \Log::warning('reCAPTCHA low score detected', [
                    'score' => $data['score'],
                    'action' => $data['action'] ?? 'unknown',
                    'ip' => request()->ip()
                ]);
                $fail('Security check failed. Suspicious activity detected.');
            }
        } catch (\Exception $e) {
            // If network error occurs, allow the request to proceed
            // Log this for monitoring purposes
            \Log::warning('reCAPTCHA validation error: ' . $e->getMessage(), [
                'ip' => request()->ip(),
                'error' => $e->getMessage()
            ]);
            return;
        }
    }
}
