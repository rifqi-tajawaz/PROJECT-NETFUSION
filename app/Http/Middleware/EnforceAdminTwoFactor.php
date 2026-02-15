<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceAdminTwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isAdmin() && !$user->hasTwoFactorEnabled()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Two-factor authentication is mandatory for administrators. Please enable it to continue.'
                ], 403);
            }

            // Avoid redirect loop if already on the profile/setup page
            if (!$request->routeIs('user.profile*') && !$request->routeIs('two-factor.*')) {
                return redirect()->route('user.profile')
                    ->with('error', 'Security Policy: Administrators must enable Two-Factor Authentication.');
            }
        }

        return $next($request);
    }
}
