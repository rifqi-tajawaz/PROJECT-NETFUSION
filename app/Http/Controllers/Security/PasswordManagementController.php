<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Rules\PasswordStrength;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PasswordManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('password.confirm')->only(['change']);
    }

    /**
     * Display password management page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        return view('security.password.index', compact('user'));
    }

    /**
     * Change user password.
     */
    public function change(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                new PasswordStrength(8, true, true, true, true, true)
            ],
        ], [
            'password.confirmed' => 'The new password confirmation does not match.',
            'current_password.required' => 'Please enter your current password.',
            'current_password.current_password' => 'The current password is incorrect.',
        ]);

        $user = Auth::user();

        // Check password history (Last 3 passwords)
        $recentPasswords = $user->passwordHistories()->latest()->take(3)->get();
        foreach ($recentPasswords as $history) {
            if (Hash::check($request->password, $history->password)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'password' => ['You cannot reuse your recent passwords. Please choose a different password.'],
                ]);
            }
        }

        // Save CURRENT password to history before updating
        $user->passwordHistories()->create([
            'password' => $user->password,
        ]);

        // Prune old history (Keep only last 3)
        $idsToKeep = $user->passwordHistories()->latest()->take(3)->pluck('id');
        $user->passwordHistories()->whereNotIn('id', $idsToKeep)->delete();

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
        ]);

        // Log security event
        \App\Models\SecurityLog::create([
            'user_id' => $user->id,
            'event_type' => 'password_changed',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details' => [
                'changed_at' => now()->toIso8601String(),
                'password_strength' => PasswordStrength::getStrengthFeedback($request->password)['strength'],
            ]
        ]);

        // Invalidate all other sessions for security
        $sessionManager = app(\App\Services\Auth\SessionManagerService::class);
        $sessionManager->invalidateOtherSessions($user->id);

        return back()->with('success', 'Password changed successfully. All other sessions have been terminated for security.');
    }

    /**
     * Check password strength via AJAX.
     */
    public function checkStrength(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:1',
        ]);

        $feedback = PasswordStrength::getStrengthFeedback($request->password);

        return response()->json($feedback);
    }

    /**
     * Get password history and requirements.
     */
    public function getPasswordInfo(Request $request)
    {
        $user = Auth::user();

        $info = [
            'requirements' => [
                'Minimum length: 8 characters',
                'Must contain uppercase letters',
                'Must contain lowercase letters',
                'Must contain numbers',
                'Must contain special characters',
                'Cannot be a common password',
                'Cannot appear in data breaches',
            ],
            'last_changed' => $user->password_changed_at?->diffForHumans(),
            'days_since_change' => $user->password_changed_at
                ? $user->password_changed_at->diffInDays(now())
                : null,
            'security_score' => $user->security_score,
            'security_level' => $user->security_level,
        ];

        return response()->json($info);
    }

    /**
     * Check if password has been pwned.
     */
    public function checkPasswordBreach(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        try {
            $hash = strtoupper(sha1($request->password));
            $prefix = substr($hash, 0, 5);
            $suffix = substr($hash, 5);

            $response = \Illuminate\Support\Facades\Http::get("https://api.pwnedpasswords.com/range/{$prefix}");

            if ($response->successful()) {
                $hashes = $response->body();

                if (strpos($hashes, $suffix) !== false) {
                    return response()->json([
                        'is_pwned' => true,
                        'message' => 'This password has been found in data breaches. Please choose a different password.'
                    ]);
                }
            }

            return response()->json([
                'is_pwned' => false,
                'message' => 'This password has not been found in known data breaches.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to check password against breach database.',
                'message' => 'Please try again later.'
            ], 500);
        }
    }

    /**
     * Generate secure password suggestions.
     */
    public function generatePasswordSuggestions(Request $request)
    {
        $length = $request->get('length', 16);
        $count = $request->get('count', 5);
        $includeSymbols = $request->get('symbols', true);

        $passwords = [];

        for ($i = 0; $i < $count; $i++) {
            $passwords[] = $this->generateSecurePassword($length, $includeSymbols);
        }

        return response()->json([
            'passwords' => $passwords
        ]);
    }

    /**
     * Generate a secure password.
     */
    protected function generateSecurePassword(int $length, bool $includeSymbols): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chars .= '0123456789';

        if ($includeSymbols) {
            $chars .= '!@#$%^&*()_+-=[]{}|;:,.<>?';
        }

        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $password;
    }
}
