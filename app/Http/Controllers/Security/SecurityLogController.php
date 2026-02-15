<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SecurityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display security logs page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = SecurityLog::where('user_id', $user->id)
            ->with('user')
            ->orderBy('created_at', 'desc');

        // Filter by event type
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by risk level
        if ($request->filled('risk_level')) {
            $riskLevels = $this->getRiskLevelsForFilter($request->risk_level);
            $query->whereIn('event_type', $riskLevels);
        }

        $logs = $query->paginate(50);

        // Get statistics
        $stats = $this->getSecurityStats($user->id);

        // Get available event types for filter
        $eventTypes = SecurityLog::where('user_id', $user->id)
            ->distinct('event_type')
            ->pluck('event_type')
            ->sort()
            ->values();

        return view('security.logs.index', compact(
            'logs',
            'stats',
            'eventTypes'
        ));
    }

    /**
     * Export security logs to CSV.
     */
    public function export(Request $request)
    {
        $user = Auth::user();

        $query = SecurityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        $filename = 'security_logs_' . $user->id . '_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // CSV header
            fputcsv($file, [
                'Date',
                'Event Type',
                'IP Address',
                'User Agent',
                'Risk Level',
                'Details'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->event_type_name,
                    $log->ip_address,
                    $this->truncateUserAgent($log->user_agent),
                    ucfirst($log->risk_level),
                    $this->formatDetails($log->details)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get security statistics.
     */
    protected function getSecurityStats(int $userId): array
    {
        $now = now();
        $last30Days = $now->copy()->subDays(30);
        $last7Days = $now->copy()->subDays(7);
        $last24Hours = $now->copy()->subHours(24);

        return [
            'total_events' => SecurityLog::where('user_id', $userId)->count(),
            'last_30_days' => SecurityLog::where('user_id', $userId)
                ->where('created_at', '>=', $last30Days)
                ->count(),
            'last_7_days' => SecurityLog::where('user_id', $userId)
                ->where('created_at', '>=', $last7Days)
                ->count(),
            'last_24_hours' => SecurityLog::where('user_id', $userId)
                ->where('created_at', '>=', $last24Hours)
                ->count(),
            'high_risk_events' => SecurityLog::where('user_id', $userId)
                ->whereIn('event_type', $this->getRiskLevelsForFilter('high'))
                ->count(),
            'failed_logins' => SecurityLog::where('user_id', $userId)
                ->where('event_type', 'login_failed')
                ->count(),
            'suspicious_activity' => SecurityLog::where('user_id', $userId)
                ->where('event_type', 'like', '%suspicious%')
                ->count(),
        ];
    }

    /**
     * Get event types by risk level for filtering.
     */
    protected function getRiskLevelsForFilter(string $riskLevel): array
    {
        $levels = [
            'high' => [
                'LOGIN_FAILED',
                '2FA_VERIFICATION_FAILED',
                'ACCOUNT_LOCKED',
                'SUSPICIOUS_ACTIVITY',
                'IP_BLOCKED',
                'suspicious_login_attempt',
            ],
            'medium' => [
                'PASSWORD_CHANGED',
                '2FA_DISABLED',
                'OAUTH_UNLINKED',
                'device_revoked',
                'session_terminated',
            ],
            'low' => [
                'LOGIN_SUCCESS',
                'LOGOUT',
                '2FA_ENABLED',
                '2FA_VERIFICATION_SUCCESS',
                'OAUTH_LINKED',
                'device_trusted',
            ],
        ];

        return $levels[$riskLevel] ?? [];
    }

    /**
     * Truncate user agent for display.
     */
    protected function truncateUserAgent(string $userAgent): string
    {
        if (strlen($userAgent) > 100) {
            return substr($userAgent, 0, 100) . '...';
        }

        return $userAgent;
    }

    /**
     * Format details for CSV export.
     */
    protected function formatDetails($details): string
    {
        if (is_array($details)) {
            return json_encode($details, JSON_PRETTY_PRINT);
        }

        return (string) $details;
    }
}
