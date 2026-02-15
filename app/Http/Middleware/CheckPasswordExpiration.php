<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Check Password Expiration Middleware
 *
 * Checks if the user's password has expired and forces password change.
 */
class CheckPasswordExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated users
        if (Auth::check()) {
            $user = $request->user();

            // Skip check if already on password change route
            if ($request->routeIs('password.change') || $request->routeIs('password.update')) {
                return $next($request);
            }

            // Check if password is expired
            if ($user->isPasswordExpired()) {
                // Store intended URL for redirect after password change
                if (!$request->session()->has('url.intended')) {
                    session(['url.intended' => $request->fullUrl()]);
                }

                // Add warning message
                $daysRemaining = $user->getPasswordExpirationDays();

                if ($user->must_change_password || $daysRemaining === 0) {
                    // Password is expired or force change required
                    return redirect()
                        ->route('password.change')
                        ->with('warning', 'Your password has expired. Please change your password to continue.');
                } elseif ($daysRemaining <= 7) {
                    // Password expiring soon - show warning but allow access
                    if ($request->acceptsHtml() && !$request->isAjax()) {
                        $message = "Your password will expire in {$daysRemaining} " .
                            str_plural('day', $daysRemaining) . ". Please change it soon.";

                        return redirect()
                            ->intended()
                            ->with('password_expiring_soon', $message);
                    }
                }
            }
        }

        return $next($request);
    }
}
