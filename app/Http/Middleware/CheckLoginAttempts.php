<?php

namespace App\Http\Middleware;

use App\Services\Auth\LoginAttemptService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Check Login Attempts Middleware
 *
 * Checks if the user has exceeded maximum login attempts
 * and blocks the request if locked out.
 */
class CheckLoginAttempts
{
    /**
     * The login attempt service instance.
     */
    protected LoginAttemptService $loginAttemptService;

    /**
     * Create a new middleware instance.
     */
    public function __construct()
    {
        $this->loginAttemptService = App::make(LoginAttemptService::class);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only check on login requests
        if ($this->isLoginRequest($request)) {
            $email = $request->input('email');
            $ipAddress = $this->getClientIp($request);

            // Check if locked out
            if ($this->loginAttemptService->isLockedOut($email, $ipAddress)) {
                return $this->lockoutResponse($request, $email, $ipAddress);
            }
        }

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function terminate($request, $response): void
    {
        // If authentication failed, record the attempt
        if ($this->isLoginRequest($request) && $response->status() === 422) {
            $email = $request->input('email');
            $ipAddress = $this->getClientIp($request);

            // Only record if there are validation errors (likely wrong credentials)
            if ($email && $response->status() === 422) {
                $this->loginAttemptService->recordFailedAttempt($email, $ipAddress);
            }
        }

        // If authentication succeeded, clear attempts
        // Note: This is handled in the controller after successful login
        // This is just a backup check
        if ($request->route()?->getName() === 'dashboard' && auth()->check()) {
            // Attempts should have been cleared in the controller
        }
    }

    /**
     * Determine if this is a login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isLoginRequest(Request $request): bool
    {
        return $request->is('login') && $request->isMethod('POST');
    }

    /**
     * Get the client IP address, accounting for proxies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getClientIp(Request $request): string
    {
        // Check for forwarded IP (proxy/load balancer)
        $forwardedIp = $request->header('X-Forwarded-For');
        if ($forwardedIp) {
            // Get the first IP in the list (original client)
            return explode(',', $forwardedIp)[0];
        }

        // Check for real IP header
        $realIp = $request->header('X-Real-IP');
        if ($realIp) {
            return $realIp;
        }

        // Fall back to standard IP
        return $request->ip();
    }

    /**
     * Return a lockout response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $email
     * @param  string  $ipAddress
     * @return \Illuminate\Http\Response
     */
    protected function lockoutResponse(Request $request, string $email, string $ipAddress)
    {
        $lockoutTime = $this->loginAttemptService->getLockoutTimeRemaining($email, $ipAddress);

        if ($lockoutTime === -1) {
            // Permanent lockout
            $message = 'Too many failed login attempts. This account/IP has been permanently blocked. '
                . 'Please contact support for assistance.';
        } else {
            // Temporary lockout
            $message = "Too many failed login attempts. Please try again in {$lockoutTime} minutes.";
        }

        // Log lockout event
        \Log::warning('Login attempt blocked due to lockout', [
            'email' => $email,
            'ip' => $ipAddress,
            'lockout_time' => $lockoutTime,
            'timestamp' => now()->toIso8601String(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'account_locked',
                'message' => $message,
                'lockout_time_remaining_minutes' => $lockoutTime,
            ], 429);
        }

        return redirect()
            ->back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => $message,
            ]);
    }
}
