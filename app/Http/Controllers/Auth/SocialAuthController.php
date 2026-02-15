<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use App\Services\Auth\Strategies\SocialAuthStrategy;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    protected $authService;
    protected $socialStrategy;

    public function __construct(AuthService $authService, SocialAuthStrategy $socialStrategy)
    {
        $this->authService = $authService;
        $this->socialStrategy = $socialStrategy;
    }

    /**
     * Redirect to Provider
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Callback from Provider
     */
    public function callback($provider, Request $request)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            $credentials = [
                'user' => $socialUser,
                'provider' => $provider
            ];

            // Use AuthService to authenticate (find or create)
            $user = $this->authService->authenticate($this->socialStrategy, $credentials, $request);

            if ($user instanceof \App\Models\User && $user->two_factor_confirmed_at) {
                // Logic for 2FA if needed
            }

            return redirect()->route('mikrotik-suite.dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Unable to login with ' . $provider . ': ' . $e->getMessage()]);
        }
    }
}
