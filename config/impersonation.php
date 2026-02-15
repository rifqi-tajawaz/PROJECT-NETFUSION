<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Impersonation Expiration Time
    |--------------------------------------------------------------------------
    |
    | This value determines how long (in minutes) an admin can impersonate
    | a user before the session automatically expires for security reasons.
    |
    | Default: 60 minutes (1 hour)
    |
    */

    'expiration_minutes' => env('IMPERSONATION_EXPIRATION_MINUTES', 60),

    /*
    |--------------------------------------------------------------------------
    | Blocked Actions During Impersonation
    |--------------------------------------------------------------------------
    |
    | These route names or patterns will be blocked while impersonating.
    | This prevents admins from performing destructive actions as users.
    |
    */

    'blocked_actions' => [
        'password.update',
        'password.confirm',
        'user-password.update',
        'two-factor.enable',
        'two-factor.confirm',
        'two-factor.disable',
        'profile.destroy',
        'verification.send',
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Impersonation Events
    |--------------------------------------------------------------------------
    |
    | When enabled, all impersonation events (start, stop, expire) will be
    | logged to the security_logs table for audit purposes.
    |
    */

    'log_events' => env('IMPERSONATION_LOG_EVENTS', true),
];
