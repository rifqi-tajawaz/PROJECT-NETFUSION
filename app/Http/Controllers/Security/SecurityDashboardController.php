<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SecurityDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Get recent activity logs
        $logs = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // 2. Get active sessions
        $sessions = [];
        if (config('session.driver') === 'database') {
            $sessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->orderBy('last_activity', 'desc')
                ->get()
                ->map(function ($session) use ($request) {
                    $ua = $session->user_agent ?? '';

                    $platform = 'Unknown';
                    if (stripos($ua, 'Windows') !== false)
                        $platform = 'Windows';
                    elseif (stripos($ua, 'Macintosh') !== false)
                        $platform = 'Mac';
                    elseif (stripos($ua, 'Linux') !== false)
                        $platform = 'Linux';
                    elseif (stripos($ua, 'Android') !== false)
                        $platform = 'Android';
                    elseif (stripos($ua, 'iPhone') !== false)
                        $platform = 'iPhone';

                    $browser = 'Unknown';
                    if (stripos($ua, 'Chrome') !== false)
                        $browser = 'Chrome';
                    elseif (stripos($ua, 'Firefox') !== false)
                        $browser = 'Firefox';
                    elseif (stripos($ua, 'Safari') !== false)
                        $browser = 'Safari';
                    elseif (stripos($ua, 'Edge') !== false)
                        $browser = 'Edge';

                    return (object) [
                        'id' => $session->id,
                        'ip_address' => $session->ip_address,
                        'is_current_device' => $session->id === $request->session()->getId(),
                        'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                        'platform' => $platform,
                        'browser' => $browser,
                        'device' => $platform, // Fallback
                    ];
                });
        }

        return view('account.security', compact('user', 'logs', 'sessions'));
    }

    public function logoutDevice(Request $request, $sessionId)
    {
        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('status', 'Device has been signed out.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->file('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');

            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $path;
            $user->save();
        }

        return back()->with('status', 'Profile picture updated!');
    }
}
