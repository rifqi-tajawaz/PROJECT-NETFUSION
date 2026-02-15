<?php

namespace App\Services\Auth\Strategies;

use App\Contracts\Auth\AuthenticationStrategy;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthStrategy implements AuthenticationStrategy
{
    protected UserRepository $userRepository;
    protected string $provider;

    public function __construct(UserRepository $userRepository, string $provider = 'google') // Default to google or pass in constructor
    {
        $this->userRepository = $userRepository;
        $this->provider = $provider;
    }

    public function setProvider(string $provider): self
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * Authenticate (or register) a user based on Socialite user data.
     * Note: This separates the "callback" logic. 
     * In a Strategy pattern, we might need a DTO, but for now we'll assume credentials contains the Socialite User or the code.
     * 
     * @param array $credentials ['driver' => 'google', 'user' => SocialiteUserObject]
     * @return Authenticatable|null
     */
    public function authenticate(array $credentials): ?Authenticatable
    {
        // ideally, the controller handles the redirect/callback, gets the social user, 
        // and passes it here to find-or-create the local user.

        if (!isset($credentials['user'])) {
            return null;
        }

        $socialUser = $credentials['user'];
        $email = $socialUser->getEmail();

        if (!$email) {
            return null;
        }

        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            // Register flow for social login
            // For now, we return null or create. Let's create.
            $user = $this->userRepository->create([
                'name' => $socialUser->getName(),
                'email' => $email,
                'password' => bcrypt(Str::random(16)), // Dummy password
                // 'email_verified_at' => now(), // Auto verify?
            ]);
        }

        return $user;
    }
}
