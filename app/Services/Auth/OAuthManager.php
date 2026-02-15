<?php

namespace App\Services\Auth;

use App\Contracts\Auth\OAuthManagerInterface;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class OAuthManager implements OAuthManagerInterface
{
    protected UserRepository $userRepository;
    protected array $supportedProviders = [
        'google',
        'facebook',
        'github',
        'twitter',
        'linkedin',
        'microsoft',
    ];

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function redirect(string $provider, array $scopes = [])
    {
        if (!$this->isProviderSupported($provider)) {
            throw new \InvalidArgumentException("OAuth provider '{$provider}' is not supported.");
        }

        $driver = Socialite::driver($provider);

        if (!empty($scopes) && method_exists($driver, 'scopes')) {
            $driver->scopes($scopes);
        }

        return $driver->redirect();
    }

    public function callback(string $provider, Request $request): ?User
    {
        if (!$this->isProviderSupported($provider)) {
            throw new \InvalidArgumentException("OAuth provider '{$provider}' is not supported.");
        }

        try {
            $socialUser = Socialite::driver($provider)->user();

            return $this->findOrCreateUser($socialUser, $provider);
        } catch (\Exception $e) {
            \Log::error('OAuth callback error', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    public function linkAccount(User $user, string $provider, array $providerData): bool
    {
        if (!$this->isProviderSupported($provider)) {
            throw new \InvalidArgumentException("OAuth provider '{$provider}' is not supported.");
        }

        // Check if account is already linked to another user
        $existingUser = $this->userRepository->findByProvider($provider, $providerData['id']);

        if ($existingUser && $existingUser->id !== $user->id) {
            throw new Exception("This {$provider} account is already linked to another user.");
        }

        $user->forceFill([
            'provider' => $provider,
            'provider_id' => $providerData['id'],
            'provider_token' => encrypt($providerData['token'] ?? ''),
            'provider_refresh_token' => encrypt($providerData['refresh_token'] ?? ''),
            'avatar' => $providerData['avatar'] ?? $user->avatar,
        ])->save();

        \Log::info('OAuth account linked', [
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $providerData['id'],
        ]);

        return true;
    }

    public function unlinkAccount(User $user, string $provider): bool
    {
        if (!$this->isProviderSupported($provider)) {
            throw new \InvalidArgumentException("OAuth provider '{$provider}' is not supported.");
        }

        // Don't allow unlinking if it's the only login method
        if ($user->provider === $provider && !$user->password) {
            throw new Exception("Cannot unlink your only login method. Please set a password first.");
        }

        $user->forceFill([
            'provider' => null,
            'provider_id' => null,
            'provider_token' => null,
            'provider_refresh_token' => null,
        ])->save();

        \Log::info('OAuth account unlinked', [
            'user_id' => $user->id,
            'provider' => $provider,
        ]);

        return true;
    }

    public function getLinkedAccounts(User $user): array
    {
        $linkedAccounts = [];

        if ($user->provider && $user->provider_id) {
            $linkedAccounts[] = [
                'provider' => $user->provider,
                'provider_id' => $user->provider_id,
                'avatar' => $user->avatar,
                'is_primary' => $user->provider === $user->provider,
            ];
        }

        return $linkedAccounts;
    }

    public function hasLinkedAccount(User $user, string $provider): bool
    {
        return $user->provider === $provider && !empty($user->provider_id);
    }

    public function getProviderUserData(string $provider, string $accessToken): ?array
    {
        if (!$this->isProviderSupported($provider)) {
            throw new \InvalidArgumentException("OAuth provider '{$provider}' is not supported.");
        }

        try {
            $driver = Socialite::driver($provider);

            // Check if userFromToken method exists
            if (method_exists($driver, 'userFromToken')) {
                $socialUser = $driver->userFromToken($accessToken);
            } else {
                // Fallback: create a mock user object
                $socialUser = new class ($accessToken) {
                    private $token;

                    public function __construct($token)
                    {
                        $this->token = $token;
                    }

                    public function getId()
                    {
                        return null;
                    }
                    public function getName()
                    {
                        return 'OAuth User';
                    }
                    public function getEmail()
                    {
                        return null;
                    }
                    public function getAvatar()
                    {
                        return null;
                    }
                    public function getRefreshToken()
                    {
                        return null;
                    }
                    public function getExpiresIn()
                    {
                        return null;
                    }
                };
            }

            return [
                'id' => $socialUser->getId(),
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'avatar' => $socialUser->getAvatar(),
                'token' => $socialUser->token,
                'refresh_token' => $socialUser->getRefreshToken(),
                'expires_in' => $socialUser->getExpiresIn(),
            ];
        } catch (\Exception $e) {
            \Log::error('OAuth user data fetch error', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function refreshToken(User $user, string $provider): ?array
    {
        if (!$this->hasLinkedAccount($user, $provider)) {
            throw new Exception("No linked {$provider} account found.");
        }

        if (empty($user->provider_refresh_token)) {
            throw new Exception("No refresh token available for {$provider} account.");
        }

        try {
            $refreshToken = decrypt($user->provider_refresh_token);
            $newUserData = $this->getProviderUserData($provider, $refreshToken);

            if ($newUserData) {
                $user->forceFill([
                    'provider_token' => encrypt($newUserData['token']),
                    'provider_refresh_token' => encrypt($newUserData['refresh_token'] ?? ''),
                ])->save();

                return $newUserData;
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('OAuth token refresh error', [
                'user_id' => $user->id,
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function revokeAccess(User $user, string $provider): bool
    {
        if (!$this->hasLinkedAccount($user, $provider)) {
            throw new Exception("No linked {$provider} account found.");
        }

        try {
            // This is provider-specific and may need custom implementation
            // For now, we'll just unlink the account locally
            return $this->unlinkAccount($user, $provider);
        } catch (\Exception $e) {
            \Log::error('OAuth access revoke error', [
                'user_id' => $user->id,
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function validateToken(string $provider, string $token): bool
    {
        if (!$this->isProviderSupported($provider)) {
            return false;
        }

        try {
            $userData = $this->getProviderUserData($provider, $token);
            return $userData !== null;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getSupportedProviders(): array
    {
        return $this->supportedProviders;
    }

    /**
     * Check if provider is supported
     */
    protected function isProviderSupported(string $provider): bool
    {
        return in_array($provider, $this->supportedProviders);
    }

    /**
     * Find or create user from OAuth data
     */
    protected function findOrCreateUser($socialUser, string $provider): User
    {
        // First try to find user by provider and provider_id
        $user = $this->userRepository->findByProvider($provider, $socialUser->getId());

        if ($user) {
            // Update existing user's OAuth data
            $user->forceFill([
                'provider_token' => encrypt($socialUser->token),
                'provider_refresh_token' => encrypt($socialUser->refreshToken ?? ''),
                'avatar' => $socialUser->getAvatar() ?? $user->avatar,
            ])->save();

            return $user;
        }

        // Try to find user by email
        if ($socialUser->getEmail()) {
            $user = $this->userRepository->findByEmail($socialUser->getEmail());

            if ($user) {
                // Link OAuth account to existing user
                $this->linkAccount($user, $provider, [
                    'id' => $socialUser->getId(),
                    'token' => $socialUser->token,
                    'refresh_token' => $socialUser->refreshToken,
                    'avatar' => $socialUser->getAvatar(),
                ]);

                return $user;
            }
        }

        // Create new user
        return $this->userRepository->create([
            'name' => $socialUser->getName() ?? 'OAuth User',
            'email' => $socialUser->getEmail() ?? "oauth_{$socialUser->getId()}@{$provider}.com",
            'password' => bcrypt(Str::random(16)), // Random password
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'provider_token' => encrypt($socialUser->token),
            'provider_refresh_token' => encrypt($socialUser->refreshToken ?? ''),
            'avatar' => $socialUser->getAvatar(),
            'email_verified_at' => now(), // Auto-verify OAuth users
        ]);
    }

    /**
     * Get OAuth provider configuration
     */
    public function getProviderConfig(string $provider): array
    {
        if (!$this->isProviderSupported($provider)) {
            return [];
        }

        return [
            'client_id' => config("services.{$provider}.client_id"),
            'client_secret' => config("services.{$provider}.client_secret"),
            'redirect' => config("services.{$provider}.redirect"),
            'scopes' => config("services.{$provider}.scopes", []),
            'enabled' => config("services.{$provider}.enabled", false),
        ];
    }

    /**
     * Check if OAuth provider is enabled
     */
    public function isProviderEnabled(string $provider): bool
    {
        $config = $this->getProviderConfig($provider);

        return !empty($config['client_id']) &&
            !empty($config['client_secret']) &&
            $config['enabled'];
    }

    /**
     * Get enabled OAuth providers
     */
    public function getEnabledProviders(): array
    {
        return array_filter($this->supportedProviders, function ($provider) {
            return $this->isProviderEnabled($provider);
        });
    }
}
