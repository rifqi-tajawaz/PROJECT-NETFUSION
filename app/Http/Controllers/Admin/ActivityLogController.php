<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'action' => 'nullable|string',
            'search' => 'nullable|string|max:255',
        ]);

        $query = ActivityLog::with('user');
        $this->applyFilters($query, $request);

        // Sort
        $query->latest();

        // Pagination
        $logs = $query->paginate(20)->withQueryString();

        // Statistics for "At a Glance"
        // Note: We might want these stats to reflect the *filtered* view or Global view?
        // Usually dashboards show Global stats at top, but filtered table. 
        // Let's keep specific stats Global for context, or we can make them filtered.
        // For "Total Events" usually means Total in DB. 
        // Let's keep them global for now as "Dashboard" widgets.
        $totalLogs = ActivityLog::count();
        $failedLogins = ActivityLog::where('action', 'LOGIN_FAILED')->count();
        $uniqueUsers = ActivityLog::distinct('user_id')->count('user_id');
        $todayLogs = ActivityLog::whereDate('created_at', now()->today())->count();

        // Chart Data (Last 7 Days)
        // This is also usually a global metric for context
        $chartData = ActivityLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        $chartLabels = $chartData->keys()->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M'));
        $chartValues = $chartData->values();

        return view('admin.activity-logs.index', compact('logs', 'totalLogs', 'failedLogins', 'uniqueUsers', 'todayLogs', 'chartLabels', 'chartValues'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $fileName = 'activity_logs_' . date('Y-m-d_H-i') . '.csv';
        $query = ActivityLog::with('user')->latest();
        $this->applyFilters($query, $request);

        $logs = $query->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Time', 'User', 'Email', 'Action', 'Description', 'IP Address', 'User Agent');

        $callback = function () use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                $row['Time'] = $log->created_at;
                $row['User'] = $log->user ? $log->user->name : 'Guest/System';
                $row['Email'] = $log->user ? $log->user->email : 'N/A';
                $row['Action'] = $log->action;
                $row['Description'] = $log->description;
                $row['IP Address'] = $log->ip_address;
                $row['User Agent'] = $log->user_agent;

                fputcsv($file, array($row['Time'], $row['User'], $row['Email'], $row['Action'], $row['Description'], $row['IP Address'], $row['User Agent']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Apply common filters query.
     */
    private function applyFilters($query, Request $request)
    {
        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Action Filter
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Date Range Filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
    }
}
