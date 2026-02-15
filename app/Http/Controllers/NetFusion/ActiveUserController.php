<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NetFusion\Modules\HotspotService;
use App\Services\NetFusion\Modules\SystemRouterService;
use Illuminate\Support\Facades\Session;
use Exception;

class ActiveUserController extends Controller
{
    protected $hotspotService;
    protected $systemService;

    public function __construct(HotspotService $hotspotService, SystemRouterService $systemService)
    {
        $this->hotspotService = $hotspotService;
        $this->systemService = $systemService;
    }

    public function index(Request $request)
    {
        $activeUsers = [];

        try {
            if (Session::has('router_session')) {
                $activeUsers = $this->hotspotService->getActiveUsers();
            }
        } catch (Exception $e) {
            return redirect()->route('mikrotik-suite.netfusion.settings.index')
                ->with('error', 'Connection error: ' . $e->getMessage());
        }

        return view('netfusion.active.index', compact('activeUsers'));
    }

    public function disconnect(Request $request, $id)
    {
        try {
            if (!Session::has('router_session')) {
                return back()->with('error', 'No active RouterOS session.');
            }

            $this->hotspotService->removeActiveUser($id);

            return back()->with('success', 'User disconnected successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to disconnect user: ' . $e->getMessage());
        }
    }

    /**
     * Disconnect multiple users
     */
    public function disconnectMultiple(Request $request)
    {
        $request->validate([
            'users' => 'required|array|min:1',
            'users.*' => 'required|string',
        ]);

        try {
            if (!Session::has('router_session')) {
                return back()->with('error', 'No active RouterOS session.');
            }

            $disconnectedCount = 0;
            $failedCount = 0;

            foreach ($request->users as $userId) {
                try {
                    $this->hotspotService->removeActiveUser($userId);
                    $disconnectedCount++;
                } catch (Exception $e) {
                    $failedCount++;
                }
            }

            $message = "Successfully disconnected {$disconnectedCount} users.";
            if ($failedCount > 0) {
                $message .= " Failed to disconnect {$failedCount} users.";
            }

            return back()->with('success', $message);
        } catch (Exception $e) {
            return back()->with('error', 'Batch disconnect failed: ' . $e->getMessage());
        }
    }

    /**
     * Get live active users data for AJAX requests
     */
    public function liveData(Request $request)
    {
        try {
            if (!Session::has('router_session')) {
                return response()->json(['error' => 'No active session'], 401);
            }

            $activeUsers = $this->hotspotService->getActiveUsers();

            // Format for JSON response
            $formattedUsers = collect($activeUsers)->map(function ($user) {
                return [
                    'id' => $user['.id'] ?? '',
                    'user' => $user['user'] ?? 'N/A',
                    'address' => $user['address'] ?? 'N/A',
                    'mac_address' => $user['mac-address'] ?? 'N/A',
                    'login_time' => $user['login-time'] ?? 'N/A',
                    'uptime' => $user['uptime'] ?? 'N/A',
                    'bytes_in' => $this->systemService->formatBytes($user['bytes-in'] ?? 0),
                    'bytes_out' => $this->systemService->formatBytes($user['bytes-out'] ?? 0),
                    'packet_in' => number_format($user['packets-in'] ?? 0),
                    'packet_out' => number_format($user['packets-out'] ?? 0),
                    'session_time_left' => $user['session-time-left'] ?? 'N/A',
                ];
            });

            return response()->json([
                'users' => $formattedUsers,
                'total' => count($formattedUsers),
                'timestamp' => now()->format('H:i:s')
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
