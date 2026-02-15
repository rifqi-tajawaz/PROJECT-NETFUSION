<?php

namespace App\Contracts\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

interface AuthenticationStrategy
{
    /**
     * Authenticate a user based on the provided credentials.
     *
     * @param array $credentials
     * @return Authenticatable|null
     */
    public function authenticate(array $credentials): ?Authenticatable;
}
