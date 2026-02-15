<?php

namespace App\Services\Auth;

use App\Contracts\Auth\AuthenticationManagerInterface;
use App\Contracts\Auth\AuthenticationStrategy;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticationManager implements AuthenticationManagerInterface
{
    protected AuthLogger $logger;
    protected UserRepository $userRepository;
    protected array $strategies = [];

    public function __construct(AuthLogger $logger, UserRepository $userRepository)
    {
        $this->logger = $logger;
        $this->userRepository = $userRepository;
    }

    /**
     * Register authentication strategy
     */
    public function registerStrategy(string $name, AuthenticationStrategy $strategy): void
    {
        $this->strategies[$name] = $strategy;
    }

    /**
     * Get authentication strategy
     */
    public function getStrategy(string $name): ?AuthenticationStrategy
    {
        return $this->strategies[$name] ?? null;
    }

    public function authenticate(array $credentials, string $provider = 'local', ?Request $request = null): ?Authenticatable
    {
        $strategy = $this->getStrategy($provider);

        if (!$strategy) {
            throw new \InvalidArgumentException("Authentication provider '{$provider}' not supported.");
        }

        $ip = $request ? $request->ip() : null;
        $userAgent = $request ? $request->userAgent() : null;

        $user = $strategy->authenticate($credentials);

        if ($user) {
            Auth::guard('web')->login($user, $credentials['remember'] ?? false);

            $this->logger->logLogin($user->getAuthIdentifier(), 'web', $ip, $userAgent);

            return $user;
        }

        $email = $credentials['email'] ?? 'unknown';
        $this->logger->logFailedLogin($email, $ip, $userAgent);

        return null;
    }

    public function register(array $data, string $provider = 'local'): Authenticatable
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ];

        if ($provider !== 'local' && isset($data['provider_id'])) {
            $userData['provider'] = $provider;
            $userData['provider_id'] = $data['provider_id'];
            $userData['email_verified_at'] = now(); // Auto-verify for OAuth
        }

        $user = $this->userRepository->create($userData);

        return $user;
    }

    public function logout(?Request $request = null, string $guard = 'web'): bool
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

        return true;
    }

    public function generateToken(Authenticatable $user, array $abilities = ['*']): array
    {
        if (!method_exists($user, 'createToken')) {
            throw new \RuntimeException('User model must use HasApiTokens trait');
        }

        /** @var \App\Models\User $user */
        $token = $user->createToken('auth_token', $abilities);

        return [
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->accessToken->expires_at,
            'abilities' => $abilities,
        ];
    }

    public function validateToken(string $token): bool
    {
        if (!str_contains($token, '|')) {
            return false;
        }

        [$id, $plainTextToken] = explode('|', $token, 2);

        $accessToken = PersonalAccessToken::find($id);

        if (!$accessToken || !hash_equals($accessToken->token, hash('sha256', $plainTextToken))) {
            return false;
        }

        if ($accessToken->expires_at && $accessToken->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function refreshToken(string $token): ?array
    {
        if (!$this->validateToken($token)) {
            return null;
        }

        [$id] = explode('|', $token, 2);
        $accessToken = PersonalAccessToken::find($id);

        if (!$accessToken || !$accessToken->tokenable) {
            return null;
        }

        // Revoke old token
        $accessToken->delete();

        // Generate new token
        return $this->generateToken($accessToken->tokenable, $accessToken->abilities);
    }

    public function revokeToken(string $token): bool
    {
        if (!str_contains($token, '|')) {
            return false;
        }

        [$id] = explode('|', $token, 2);
        $accessToken = PersonalAccessToken::find($id);

        if ($accessToken) {
            return $accessToken->delete();
        }

        return false;
    }
}
