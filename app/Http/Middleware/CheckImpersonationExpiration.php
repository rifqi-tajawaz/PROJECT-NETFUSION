<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckImpersonationExpiration
{
    /**
     * Handle an incoming request.
     *
     * Impersonation time limit disabled - admins can use Ghost Mode indefinitely.
     * Only tracks impersonation status without expiration.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // No time limit - admins can impersonate users indefinitely
        // Ghost Mode will only end when admin manually clicks "Stop Impersonation"

        return $next($request);
    }
}
