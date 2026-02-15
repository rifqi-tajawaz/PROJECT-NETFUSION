<?php

namespace App\Services\Auth;

use App\Contracts\Auth\AuthenticationStrategy; // Corrected namespace if needed
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected AuthLogger $logger;
    protected UserRepository $userRepository;

    public function __construct(AuthLogger $logger, UserRepository $userRepository)
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
    }

    /**
     * Attempt to authenticate a user using a specific strategy.
     *
     * @param AuthenticationStrategy $strategy
     * @param array $credentials
     * @param Request|null $request
     * @param string $guard
     * @return Authenticatable|null
     */
    public function authenticate(AuthenticationStrategy $strategy, array $credentials, ?Request $request = null, string $guard = 'web'): ?Authenticatable
    {
        $user = $strategy->authenticate($credentials);

        $ip = $request ? $request->ip() : null;
        $userAgent = $request ? $request->userAgent() : null;

        if ($user) {
            Auth::guard($guard)->login($user, $credentials['remember'] ?? false);

            // Log successful login
            $this->logger->logLogin($user->getAuthIdentifier(), $guard, $ip, $userAgent);

            return $user;
        }

        // Log failed attempt
        $email = $credentials['email'] ?? 'unknown';
        $this->logger->logFailedLogin($email, $ip, $userAgent);

        return null;
    }

    /**
     * Register a new user.
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Send email verification
        // Removed: Handled by Registered event in RegisterController
        // if ($user && env('MUST_VERIFY_EMAIL', true)) {
        //     $user->sendEmailVerificationNotification();
        // }

        // Log registration
        $this->logger->logRegistration($user);

        return $user;
    }

    /**
     * Logout the currently authenticated user.
     * 
     * @param string $guard
     * @param Request|null $request
     */
    public function logout(?Request $request = null, string $guard = 'web'): void
    {
        $user = Auth::guard($guard)->user();

        if ($user) {
            $ip = $request ? $request->ip() : null;
            $userAgent = $request ? $request->userAgent() : null;
            $this->logger->logLogout($user->getAuthIdentifier(), $ip, $userAgent);
        }

        Auth::guard($guard)->logout();

        if ($request) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
    }
}
