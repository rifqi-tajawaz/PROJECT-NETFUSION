<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckMembership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $requiredPackage = 'Free'): Response
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Admin bypass
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if expired
        if ($user->membership_status === 'expired') {
            return redirect()->route('pricing')->with('error', 'Your subscription has expired. Please renew to access this feature.');
        }

        // Check expiration date validity
        if ($user->membership_expire && Carbon::parse($user->membership_expire)->isPast()) {
            // Auto expire if date is past
            $user->update(['membership_status' => 'expired']);
            return redirect()->route('pricing')->with('error', 'Your subscription has expired. Please renew.');
        }

        // Check Package Level
        // Logic: Premium > Basic > Free
        $levels = ['Free' => 0, 'Basic' => 1, 'Premium' => 2];
        $userLevel = $levels[$user->membership_package] ?? 0; // Default to Free if unknown
        $requiredLevel = $levels[$requiredPackage] ?? 0;

        if ($userLevel < $requiredLevel) {
            return redirect()->route('pricing')->with('warning', "You need the {$requiredPackage} plan to access this feature.");
        }

        return $next($request);
    }
}
