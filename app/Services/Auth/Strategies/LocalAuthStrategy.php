<?php

namespace App\Services\Auth\Strategies;

use App\Contracts\Auth\AuthenticationStrategy;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

class LocalAuthStrategy implements AuthenticationStrategy
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Authenticate a user based on email and password.
     *
     * @param array $credentials
     * @return Authenticatable|null
     */
    public function authenticate(array $credentials): ?Authenticatable
    {
        if (!isset($credentials['email']) || !isset($credentials['password'])) {
            return null;
        }

        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user) {
            return null;
        }

        if (!Hash::check($credentials['password'], $user->getAuthPassword())) {
            return null;
        }

        return $user;
    }
}
