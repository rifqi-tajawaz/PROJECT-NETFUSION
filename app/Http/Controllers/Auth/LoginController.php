<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use App\Services\Auth\LoginAttemptService;
use App\Services\Auth\Strategies\LocalAuthStrategy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen.
    |
    */

    protected $authService;
    protected $localStrategy;
    protected $loginAttemptService;

    protected $redirectTo = '/mikrotik-suite/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AuthService $authService,
        LocalAuthStrategy $localStrategy,
        LoginAttemptService $loginAttemptService
    ) {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');

        $this->authService = $authService;
        $this->localStrategy = $localStrategy;
        $this->loginAttemptService = $loginAttemptService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['remember'] = $request->filled('remember');

        // Pre-check: find user to validate status
        $this->checkUserStatus($request);

        if ($user = $this->authService->authenticate($this->localStrategy, $credentials, $request)) {
            return $this->handleUserAuthenticated($request, $user);
        }

        // Log failed attempt for security monitoring
        $this->handleFailedLogin($request);
    }

    /**
     * Check user status before attempting login.
     */
    protected function checkUserStatus(Request $request): void
    {
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user) {
            // Check if user can login
            if (!$user->canLogin()) {
                $reason = $this->getLoginBlockReason($user);
                throw ValidationException::withMessages([
                    'email' => [$reason],
                ]);
            }

            // Check for suspicious activity
            $this->checkSuspiciousActivity($request, $user);
        }
    }

    /**
     * Handle successful user authentication.
     */
    protected function handleUserAuthenticated(Request $request, $user)
    {
        /** @var \App\Models\User $user */
        try {
            // Device fingerprinting
            $deviceFingerprint = app(\App\Services\Auth\DeviceFingerprintService::class);
            $device = $deviceFingerprint->registerDevice($request, $user->id);

            // Update user login info
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Check if additional verification needed
            if ($this->requiresAdditionalVerification($request, $user, $device)) {
                $request->session()->put('auth.pending_verification', [
                    'user_id' => $user->id,
                    'device_fingerprint' => $device->fingerprint,
                    'redirect_to' => $request->input('redirect', $this->redirectTo),
                ]);

                return $request->wantsJson()
                    ? response()->json(['redirect' => route('auth.verification.required')])
                    : redirect()->route('auth.verification.required');
            }
        } catch (\Throwable $e) {
            Log::error("Login post-processing failed: " . $e->getMessage());
        }

        $request->session()->regenerate();

        return $this->authenticated($request, $user)
            ?: redirect()->intended($this->redirectTo);
    }

    /**
     * Handle failed login attempt.
     */
    protected function handleFailedLogin(Request $request): void
    {
        $user = \App\Models\User::where('email', $request->email)->first();
        
        $this->logFailedLoginAttempt($request, $user);

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Clear session ID from database
        if ($user = $request->user()) {
            $user->forceFill(['current_session_id' => null])->save();
        }

        $this->authService->logout($request);

        return $request->wantsJson()
            ? response()->json([], 204)
            : redirect('/');
    }

    /**
     * Get login block reason.
     */
    protected function getLoginBlockReason($user): string
    {
        if ($user->isLocked()) {
            $lockTime = $user->locked_at ? $user->locked_at->diffForHumans() : 'recently';
            return "Account is locked due to security concerns. Locked {$lockTime}.";
        }

        if (!$user->isActive()) {
            return "Account is inactive. Please contact support.";
        }

        if (!$user->hasVerifiedEmail()) {
            return "Please verify your email address before logging in.";
        }

        return "Access denied.";
    }

    /**
     * Check for suspicious activity.
     */
    protected function checkSuspiciousActivity(Request $request, $user): void
    {
        $deviceFingerprint = app(\App\Services\Auth\DeviceFingerprintService::class);
        $suspicious = $deviceFingerprint->detectSuspiciousActivity($request, $user->id);

        if (!empty($suspicious['suspicious'])) {
            // Log suspicious activity
            \App\Models\SecurityLog::create([
                'user_id' => $user->id,
                'event_type' => 'suspicious_login_attempt',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details' => $suspicious
            ]);

            // Temporarily lock account if high risk
            if ($suspicious['risk_score'] > 70) {
                $user->update([
                    'is_locked' => true,
                    'locked_at' => now(),
                    'lock_reason' => 'Suspicious login activity detected'
                ]);

                // Send security alert
                Mail::to($user->email)->send(new \App\Mail\SecurityAlert($suspicious));
            }
        }
    }

    /**
     * Check if additional verification is required.
     */
    protected function requiresAdditionalVerification(Request $request, $user, $device): bool
    {
        // 2FA required if enabled
        if ($user->two_factor_confirmed_at) {
            return true;
        }

        // Require verification for new/untrusted devices
        if (!$device->is_trusted) {
            return true;
        }

        // Check for suspicious patterns
        $deviceFingerprint = app(\App\Services\Auth\DeviceFingerprintService::class);
        $suspicious = $deviceFingerprint->detectSuspiciousActivity($request, $user->id);

        return !empty($suspicious['suspicious']);
    }

    /**
     * Log failed login attempt.
     */
    protected function logFailedLoginAttempt(Request $request, $user): void
    {
        $data = [
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        if ($user) {
            // Increment failed attempts
            $user->increment('login_attempts');

            // Lock account after too many failed attempts
            if ($user->login_attempts >= 5) {
                $user->update([
                    'is_locked' => true,
                    'locked_at' => now(),
                    'lock_reason' => 'Too many failed login attempts'
                ]);

                // Send account locked email
                Mail::to($user->email)->send(new \App\Mail\AccountLocked($user));
            }

            $data['user_id'] = $user->id;
            $data['login_attempts'] = $user->login_attempts;
        }

        \App\Models\SecurityLog::create([
            'user_id' => $user->id ?? null,
            'event_type' => 'login_failed',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details' => $data
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Clear login attempts from cache (IP-based lockout)
        $this->loginAttemptService->clearAttempts(
            $user->email,
            $this->getClientIp($request)
        );

        // Reset failed attempts on successful login (database)
        if ($user->login_attempts > 0) {
            $user->update(['login_attempts' => 0]);
        }

        // Single Session Enforcement: Store the current session ID
        $user->forceFill([
            'current_session_id' => $request->session()->getId(),
        ])->save();

        if ($request->wantsJson()) {
            if ($user->two_factor_confirmed_at) {
                return response()->json(['redirect' => route('two-factor.challenge')]);
            }
            return response()->json(['redirect' => route('mikrotik-suite.dashboard')]);
        }

        if ($user->two_factor_confirmed_at) {
            return redirect()->route('two-factor.challenge');
        }

        return redirect()->route('mikrotik-suite.dashboard');
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
}
