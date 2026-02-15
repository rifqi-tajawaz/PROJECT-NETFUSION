<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Stats for cards
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'admins' => User::where('role', 'admin')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
        ];

        // Query with search/filter
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\pL\s\-\.]+$/u'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed', new StrongPassword],
                'role' => ['required', 'string', 'in:admin,user'],
                'status' => ['required', 'in:active,inactive'],
            ]);

            // Disable email verification events during admin user creation
            $oldDispatcher = User::getEventDispatcher();
            User::unsetEventDispatcher();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'],
                'is_active' => $validated['status'] === 'active',
                'email_verified_at' => now(), // Auto-verify accounts created by admin
            ]);

            // Re-enable event dispatcher
            User::setEventDispatcher($oldDispatcher);

            // Set password expiration
            $user->setPasswordExpiration();

            // Log activity
            Log::info('User created by admin', [
                'admin_id' => Auth::id(),
                'user_id' => $user->id,
                'user_email' => $user->email,
                'role' => $user->role,
            ]);

            return back()
                ->with('success', "User {$user->name} created successfully.")
                ->with('user_created', $user->id);

        } catch (\Exception $e) {
            Log::error('Failed to create user', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\pL\s\-\.]+$/u'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'role' => ['required', 'string', 'in:admin,user'],
                'status' => ['required', 'in:active,inactive'],
                'password' => ['nullable', 'string', 'min:8', 'confirmed', new StrongPassword],
            ]);

            $oldValues = $user->only(['name', 'email', 'role', 'is_active']);
            $passwordChanged = !empty($validated['password']);

            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];
            $user->is_active = $validated['status'] === 'active';

            if ($passwordChanged) {
                $user->password = bcrypt($validated['password']);
                // Reset password expiration when password is changed
                $user->setPasswordExpiration();
            }

            $user->save();

            // Log changes
            $changes = array_diff_assoc($user->only(['name', 'email', 'role', 'is_active']), $oldValues);

            Log::info('User updated by admin', [
                'admin_id' => Auth::id(),
                'user_id' => $user->id,
                'changes' => $changes,
                'password_changed' => $passwordChanged,
            ]);

            $message = $passwordChanged
                ? "User {$user->name} updated successfully. Password changed."
                : "User {$user->name} updated successfully.";

            return back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Failed to update user', [
                'admin_id' => Auth::id(),
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            if ($user->id === Auth::id()) {
                return back()->with('error', 'You cannot delete your own account.');
            }

            $userName = $user->name;
            $userId = $user->id;

            $user->delete();

            // Log activity
            Log::warning('User deleted by admin', [
                'admin_id' => Auth::id(),
                'deleted_user_id' => $userId,
                'deleted_user_name' => $userName,
                'deleted_user_email' => $user->email,
            ]);

            return back()->with('success', "User {$userName} deleted successfully.");

        } catch (\Exception $e) {
            Log::error('Failed to delete user', [
                'admin_id' => Auth::id(),
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Impersonate a user.
     */
    public function impersonate(User $user)
    {
        try {
            if ($user->id === Auth::id()) {
                return back()->with('error', 'You cannot impersonate yourself.');
            }

            if ($user->isAdmin()) {
                return back()->with('error', 'You cannot impersonate another admin.');
            }

            $admin = Auth::user();

            // Store impersonation details in session
            session()->put('impersonated_by', $admin->id);
            session()->put('impersonation_started_at', now());
            session()->put('impersonated_user_name', $user->name);
            session()->put('impersonated_user_email', $user->email);

            // Log impersonation start
            \App\Models\SecurityLog::create([
                'user_id' => $user->id,
                'event_type' => 'impersonation_started',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => [
                    'admin_id' => $admin->id,
                    'admin_name' => $admin->name,
                    'admin_email' => $admin->email,
                    'impersonated_user_id' => $user->id,
                    'impersonated_user_name' => $user->name,
                    'impersonated_user_email' => $user->email,
                    'started_at' => now()->toDateTimeString(),
                ]
            ]);

            Auth::login($user);

            return redirect()->route('mikrotik-suite.dashboard')
                ->with('success', "You are now impersonating {$user->name}")
                ->with('impersonation_active', true);

        } catch (\Exception $e) {
            Log::error('Failed to impersonate user', [
                'admin_id' => Auth::id(),
                'target_user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to impersonate user: ' . $e->getMessage());
        }
    }

    /**
     * Stop impersonating.
     */
    public function stopImpersonation()
    {
        try {
            if (session()->has('impersonated_by')) {
                $adminId = session('impersonated_by');
                $impersonatedUser = Auth::user();
                $startedAt = session('impersonation_started_at');
                $duration = $startedAt ? now()->diffInMinutes($startedAt) : 0;

                // Log impersonation stop
                \App\Models\SecurityLog::create([
                    'user_id' => $impersonatedUser->id,
                    'event_type' => 'impersonation_stopped',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'details' => [
                        'admin_id' => $adminId,
                        'impersonated_user_id' => $impersonatedUser->id,
                        'impersonated_user_name' => $impersonatedUser->name,
                        'started_at' => $startedAt?->toDateTimeString(),
                        'stopped_at' => now()->toDateTimeString(),
                        'duration_minutes' => $duration,
                    ]
                ]);

                Auth::loginUsingId($adminId);
                session()->forget(['impersonated_by', 'impersonation_started_at', 'impersonated_user_name', 'impersonated_user_email']);

                return redirect()->route('admin.users.index')
                    ->with('success', 'Welcome back, Admin!')
                    ->with('impersonation_ended', true);
            }

            return redirect()->back();

        } catch (\Exception $e) {
            Log::error('Failed to stop impersonation', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to stop impersonation: ' . $e->getMessage());
        }
    }
}
