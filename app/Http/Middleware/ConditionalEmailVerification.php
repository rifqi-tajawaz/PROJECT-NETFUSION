<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConditionalEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If email verification is disabled, skip the check
        if (!env('MUST_VERIFY_EMAIL', true)) {
            return $next($request);
        }

        // If email verification is enabled, check if user is verified
        if (!$request->user() || !$request->user()->hasVerifiedEmail()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Your email address is not verified.'], 403)
                : redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
