<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Services\Auth\SessionManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionManagementController extends Controller
{
    protected $sessionService;

    public function __construct(SessionManagerService $sessionService)
    {
        $this->sessionService = $sessionService;
        $this->middleware('auth');
    }

    /**
     * Display session management page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $sessions = $this->sessionService->getUserSessions($user->id);
        $sessionStats = $this->sessionService->getSessionStatsByDevice($user->id);
        $hasMultipleSessions = $this->sessionService->hasMultipleSessions($user->id);

        return view('security.sessions.index', compact(
            'sessions',
            'sessionStats',
            'hasMultipleSessions'
        ));
    }

    /**
     * Terminate a specific session.
     */
    public function terminate(Request $request, $sessionId)
    {
        $success = $this->sessionService->invalidateSession($sessionId, Auth::id());

        if ($success) {
            return back()->with('success', 'Session terminated successfully.');
        }

        return back()->with('error', 'Unable to terminate session.');
    }

    /**
     * Terminate all sessions except current.
     */
    public function terminateOthers(Request $request)
    {
        $count = $this->sessionService->invalidateOtherSessions(Auth::id());

        return back()->with('success', "Terminated {$count} other session(s).");
    }

    /**
     * Terminate all sessions including current (will logout user).
     */
    public function terminateAll(Request $request)
    {
        $count = $this->sessionService->invalidateAllSessions(Auth::id());

        Auth::logout();

        return redirect('/login')
            ->with('success', "Terminated all {$count} session(s) for security.");
    }

    /**
     * Get login history for the user.
     */
    public function loginHistory(Request $request)
    {
        $days = $request->get('days', 30);
        $history = $this->sessionService->getLoginHistory(Auth::id(), $days);

        return response()->json($history);
    }

    /**
     * Check for concurrent suspicious logins.
     */
    public function checkConcurrentLogins(Request $request)
    {
        $concurrent = $this->sessionService->detectConcurrentLogins(Auth::id());

        return response()->json($concurrent);
    }
}
