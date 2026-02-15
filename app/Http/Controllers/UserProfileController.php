<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Services\Auth\TwoFactorService;

class UserProfileController extends Controller
{
    protected $twoFactor;

    public function __construct(TwoFactorService $twoFactor)
    {
        $this->middleware('auth');
        $this->twoFactor = $twoFactor;
    }

    /**
     * Display the user's profile form.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $activities = $user->securityLogs()->latest()->take(10)->get();
        $sessions = []; // Session management requires database driver

        // Prepare 2FA Data
        $twoFactorData = null;
        if (!$user->two_factor_confirmed_at) {
            // If not confirmed, get or generate secret/QR for setup
            if (!$user->two_factor_secret) {
                $twoFactorData = $this->twoFactor->generateSecret($user);
            } else {
                $twoFactorData = $this->twoFactor->getQrCode($user);
            }
        }

        return view('account.profile', compact('activities', 'sessions', 'user', 'twoFactorData'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:users,email,' . $user->id,
                new \App\Rules\NotDisposableEmail
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $changes = [];
        if ($user->name !== $validated['name']) $changes['name'] = ['old' => $user->name, 'new' => $validated['name']];
        if ($user->email !== $validated['email']) $changes['email'] = ['old' => $user->email, 'new' => $validated['email']];
        if ($user->phone !== $validated['phone']) $changes['phone'] = ['old' => $user->phone, 'new' => $validated['phone']];
        if ($user->address !== $validated['address']) $changes['address'] = ['old' => $user->address, 'new' => $validated['address']];

        $user->forceFill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ])->save();

        if (!empty($changes)) {
            \App\Models\SecurityLog::create([
                'user_id' => $user->id,
                'event_type' => 'profile_updated',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details' => $changes
            ]);
        }

        return back()->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required', 
                'confirmed', 
                \Illuminate\Validation\Rules\Password::defaults(),
                new \App\Rules\PasswordStrength(8, true, true, true, true, true)
            ],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        // Check password history (Last 3 passwords)
        $recentPasswords = $user->passwordHistories()->latest()->take(3)->get();
        foreach ($recentPasswords as $history) {
            if (Hash::check($validated['password'], $history->password)) {
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

        $user->update([
            'password' => Hash::make($validated['password']),
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
                'method' => 'profile_settings'
            ]
        ]);

        // Invalidate all other sessions for security
        try {
            $sessionManager = app(\App\Services\Auth\SessionManagerService::class);
            $sessionManager->invalidateOtherSessions($user->id);
        } catch (\Throwable $e) {
            // Ignore if service not found or fails
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Password successfully updated.',
            ]);
        }

        return back()->with('status', 'password-updated');
    }

    /**
     * Update the user's preferences.
     */
    public function updatePreferences(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->forceFill([
            'login_alerts' => $request->has('login_alerts'),
            'email_notifications' => $request->has('email_notifications'),
        ])->save();

        return back()->with('status', 'preferences-updated');
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
