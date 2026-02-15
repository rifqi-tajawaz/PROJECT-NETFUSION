<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Auth\TwoFactorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    protected $twoFactor;

    public function __construct(TwoFactorService $twoFactor)
    {
        $this->middleware('auth');
        $this->twoFactor = $twoFactor;
    }

    /**
     * Show 2FA Setup Page
     */
    public function showSetup()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->two_factor_confirmed_at) {
            return redirect()->route('mikrotik-suite.dashboard')->with('status', '2FA is already enabled.');
        }

        // Generate secret if not exists
        if (!$user->two_factor_secret) {
            $data = $this->twoFactor->generateSecret($user);
            session()->put('2fa:secret', $data['secret']);
            session()->put('2fa:qr', $data['qr_code']);
        }

        return view('auth.two-factor-setup', [
            'qrCode' => session('2fa:qr'),
            'secret' => session('2fa:secret')
        ]);
    }

    /**
     * Verify 2FA challenge (Login)
     */
    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($this->twoFactor->verify($user, $request->code)) {
            session(['two_factor_verified' => true]);
            return redirect()->intended(route('mikrotik-suite.dashboard'));
        }

        throw ValidationException::withMessages([
            'code' => 'The provided Two-Factor Authentication code was invalid.',
        ]);
    }

    /**
     * Confirm 2FA setup (First time enable)
     */
    public function confirm(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($this->twoFactor->verify($user, $request->code)) {
            $user->forceFill([
                'two_factor_confirmed_at' => now(),
            ])->save();

            session(['two_factor_verified' => true]); // Also mark as verified immediately

            return redirect()->route('mikrotik-suite.dashboard')->with('success', 'Two-Factor Authentication enabled successfully!');
        }

        throw ValidationException::withMessages([
            'code' => 'The provided Two-Factor Authentication code was invalid.',
        ]);
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate(['password' => 'required|current_password']);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return back()->with('success', 'Two-Factor Authentication disabled.');
    }

    /**
     * Show Recovery Code Form
     */
    public function showRecoveryForm()
    {
        return view('auth.two-factor-recovery');
    }

    /**
     * Verify Recovery Code
     */
    public function verifyRecovery(Request $request)
    {
        $request->validate(['recovery_code' => 'required|string']);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Decode recovery codes (stored as encrypted JSON usually, but assuming simple array/json for now based on context)
        // Adjust this logic if your recovery codes are encrypted or stored differently
        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        if (!$recoveryCodes) {
            throw ValidationException::withMessages([
                'recovery_code' => 'No recovery codes available.',
            ]);
        }

        // Search for code
        $key = array_search($request->recovery_code, $recoveryCodes);

        if ($key !== false) {
            // Remove used code
            unset($recoveryCodes[$key]);

            // Update user codes
            $user->forceFill([
                'two_factor_recovery_codes' => encrypt(json_encode(array_values($recoveryCodes))),
            ])->save();

            // Login Success
            session(['two_factor_verified' => true]);
            return redirect()->intended(route('mikrotik-suite.dashboard'));
        }

        throw ValidationException::withMessages([
            'recovery_code' => 'The provided recovery code was invalid.',
        ]);
    }
}
