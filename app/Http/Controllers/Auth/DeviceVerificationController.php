<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\DeviceFingerprintService;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DeviceVerificationController extends Controller
{
    protected $deviceService;

    public function __construct(DeviceFingerprintService $deviceService)
    {
        $this->deviceService = $deviceService;
        $this->middleware('guest');
    }

    /**
     * Handle device verification.
     */
    public function verify(Request $request)
    {
        $pending = session('auth.pending_verification');

        if (!$pending) {
            return redirect()->route('login');
        }

        $request->validate([
            'verification_code' => ['required', 'string'],
        ]);

        $user = User::find($pending['user_id']);

        if (!$user) {
            return back()->with('error', 'Invalid verification session.');
        }

        // For now, accept any code (you might want to implement email verification here)
        // In a real implementation, you would send a verification code to the user's email

        // Trust the device after successful verification
        $this->deviceService->trustDevice($pending['device_fingerprint'], $user->id);

        // Clear pending verification
        session()->forget('auth.pending_verification');

        // Log the user in
        Auth::login($user);

        // Log security event
        \App\Models\SecurityLog::create([
            'user_id' => $user->id,
            'event_type' => 'device_verified',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details' => [
                'device_fingerprint' => $pending['device_fingerprint'],
                'verified_at' => now()->toIso8601String(),
            ]
        ]);

        return redirect()->intended($pending['redirect_to'] ?? '/mikrotik-suite/dashboard');
    }

    /**
     * Resend verification code.
     */
    public function resend(Request $request)
    {
        $pending = session('auth.pending_verification');

        if (!$pending) {
            return redirect()->route('login');
        }

        $user = User::find($pending['user_id']);

        if (!$user) {
            return back()->with('error', 'Invalid verification session.');
        }

        // Generate and send verification code
        $code = $this->generateVerificationCode();

        // Store code temporarily
        session([
            'auth.verification_code' => [
                'code' => Hash::make($code),
                'expires_at' => now()->addMinutes(10),
            ]
        ]);

        // Send email (you would implement this)
        // Mail::to($user->email)->send(new DeviceVerificationMail($code));

        return back()->with('success', 'Verification code sent to your email.');
    }

    /**
     * Generate verification code.
     */
    protected function generateVerificationCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
