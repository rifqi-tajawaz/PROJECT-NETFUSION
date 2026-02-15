<?php

return [
    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your reCAPTCHA settings for your application.
    |
    */

    'enabled' => env('RECAPTCHA_ENABLED', false),
    'site_key' => env('RECAPTCHA_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    'version' => env('RECAPTCHA_VERSION', 'v2'),
    'type' => env('RECAPTCHA_TYPE', 'checkbox'),
];