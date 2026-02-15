<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Auth\DeviceFingerprintService;

class DeviceFingerprintMiddleware
{
    protected $deviceService;

    public function __construct(DeviceFingerprintService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip device fingerprinting for API routes
        if ($request->is('api/*')) {
            return $next($request);
        }

        // Generate device fingerprint
        $fingerprint = $this->deviceService->generateFingerprint($request);

        // Store in session for later use
        session(['device_fingerprint' => $fingerprint]);

        return $next($request);
    }
}
