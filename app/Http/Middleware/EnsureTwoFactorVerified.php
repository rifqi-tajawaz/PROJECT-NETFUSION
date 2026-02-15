<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->two_factor_confirmed_at && !session('two_factor_verified')) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Two factor authentication required.'], 403);
            }

            return redirect()->route('two-factor.challenge');
        }

        return $next($request);
    }
}
