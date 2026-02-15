<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventActionsWhileImpersonating
{
    /**
     * Handle an incoming request.
     *
     * Prevents sensitive actions while admin is impersonating a user.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('impersonated_by')) {
            // Get the action being attempted
            $route = $request->route();
            $routeName = $route ? $route->getName() : '';

            // List of blocked actions during impersonation
            $blockedActions = [
                'password.update',
                'password.confirm',
                'user-password.update',
                'two-factor.enable',
                'two-factor.confirm',
                'two-factor.disable',
                'profile.destroy',
                'verification.send',
            ];

            // Check if current route is blocked
            foreach ($blockedActions as $blocked) {
                if (str_contains($routeName, $blocked)) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'This action is not allowed while impersonating a user.'
                        ], 403);
                    }

                    return back()->with('error', 'This action is not allowed while impersonating a user. Please exit ghost mode first.');
                }
            }

            // Also check for specific methods that might be destructive
            if ($request->isMethod('delete')) {
                return back()->with('error', 'Deletion is not allowed while impersonating a user.');
            }
        }

        return $next($request);
    }
}
