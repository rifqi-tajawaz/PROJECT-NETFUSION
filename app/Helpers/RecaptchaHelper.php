<?php

namespace App\Helpers;

class RecaptchaHelper
{
    /**
     * Check if reCAPTCHA should be displayed
     */
    public static function shouldDisplayRecaptcha(): bool
    {
        // Check if reCAPTCHA is enabled in config
        if (!config('recaptcha.enabled', false)) {
            return false;
        }

        // Check if site key is configured
        if (!env('RECAPTCHA_SITE_KEY')) {
            return false;
        }

        // Check if secret key is configured
        if (!env('RECAPTCHA_SECRET_KEY')) {
            return false;
        }

        return true;
    }

    /**
     * Get reCAPTCHA site key
     */
    public static function getSiteKey(): ?string
    {
        return self::shouldDisplayRecaptcha() ? env('RECAPTCHA_SITE_KEY') : null;
    }

    /**
     * Check if reCAPTCHA validation should be required
     */
    public static function shouldValidateRecaptcha(): bool
    {
        // You can add additional logic here, like checking if reCAPTCHA
        // service is currently available via health check
        return self::shouldDisplayRecaptcha();
    }
}
