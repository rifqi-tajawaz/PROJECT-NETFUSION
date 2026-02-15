<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NetFusion\Modules\HotspotService;
use App\Services\NetFusion\Modules\SystemRouterService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use Exception;

class ReportController extends Controller
{
    protected $hotspotService;
    protected $systemService;

    public function __construct(HotspotService $hotspotService, SystemRouterService $systemService)
    {
        $this->hotspotService = $hotspotService;
        $this->systemService = $systemService;
    }

    /**
     * Selling Report Index
     */
    public function index(Request $request)
    {
        $reports = [];
        $monthlyReport = [];
        $totalIncome = 0;
        $selectedMonth = $request->query('month');
        $selectedDay = $request->query('day');
        $months = [];

        try {
            if (Session::has('router_session')) {
                // Get available months
                $months = $this->hotspotService->getReportMonths();

                // Get reports with filters
                $reports = $this->hotspotService->getSellingReports($selectedMonth, $selectedDay);

                // Calculate totals
                $totalIncome = collect($reports)->sum('price');

                // Group by month for summary
                $monthlyReport = collect($reports)->groupBy(function ($item) {
                    return $item['datetime']->format('F Y');
                })->map(function ($group) {
                    return [
                        'month' => $group->first()['datetime']->format('F Y'),
                        'count' => $group->count(),
                        'total' => $group->sum('price'),
                    ];
                })->sortByDesc('month')->values()->all();
            }
        } catch (Exception $e) {
            // Handle gracefully
            return back()->with('error', 'Failed to load reports: ' . $e->getMessage());
        }

        return view('netfusion.reports.selling', compact(
            'reports',
            'monthlyReport',
            'totalIncome',
            'selectedMonth',
            'selectedDay',
            'months'
        ));
    }

    /**
     * Add manual selling report entry
     */
    public function storeSelling(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'price' => 'required|integer|min:0',
            'profile' => 'nullable|string',
            'comment' => 'nullable|string',
        ]);

        try {
            if (!Session::has('router_session')) {
                return back()->with('error', 'No active RouterOS session.');
            }

            $this->hotspotService->saveSellingReport([
                'username' => $request->username,
                'price' => $request->price,
                'profile' => $request->profile,
                'comment' => $request->comment,
            ]);

            return back()->with('success', 'Report entry added successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to add report: ' . $e->getMessage());
        }
    }

    /**
     * Export selling report to CSV
     */
    public function exportSellingCsv(Request $request)
    {
        try {
            if (!Session::has('router_session')) {
                return back()->with('error', 'No active RouterOS session.');
            }

            $month = $request->query('month');
            $day = $request->query('day');

            $reports = $this->hotspotService->getSellingReports($month, $day);

            $filename = 'selling_report_' . ($month ?: 'all') . '_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function () use ($reports) {
                $file = fopen('php://output', 'w');

                // CSV Header
                fputcsv($file, ['Date', 'Time', 'Username', 'Price', 'Profile', 'Comment']);

                // CSV Data
                foreach ($reports as $report) {
                    fputcsv($file, [
                        $report['date'],
                        $report['time'],
                        $report['username'],
                        number_format($report['price'], 0, '', '.'),
                        $report['profile'],
                        $report['comment'] ?? '',
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (Exception $e) {
            return back()->with('error', 'Failed to export: ' . $e->getMessage());
        }
    }

    /**
     * Print selling report
     */
    public function printSelling(Request $request)
    {
        $month = $request->query('month');
        $day = $request->query('day');
        $reports = [];
        $summary = [];

        try {
            if (Session::has('router_session')) {
                $reports = $this->hotspotService->getSellingReports($month, $day);

                $summary = [
                    'total_count' => count($reports),
                    'total_price' => collect($reports)->sum('price'),
                    'period' => $month ? Carbon::createFromFormat('mY', $month)->format('F Y') : 'All Time',
                ];
            }
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load reports: ' . $e->getMessage());
        }

        return view('netfusion.reports.print', compact('reports', 'summary'));
    }

    /**
     * Delete selling report entry
     */
    public function destroySelling($id)
    {
        try {
            if (!Session::has('router_session')) {
                return back()->with('error', 'No active RouterOS session.');
            }

            $this->hotspotService->deleteSellingReport($id);

            return back()->with('success', 'Report entry deleted successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }

    /**
     * User Log Report (Hotspot authentication logs)
     */
    public function logs(Request $request)
    {
        $logs = [];
        $topics = ['system', 'error', 'warning', 'info', 'account', 'hotspot', 'dhcp', 'pppoe', 'debug'];
        $selectedTopic = $request->query('topic');
        $search = $request->query('search');

        try {
            if (Session::has('router_session')) {
                // Increase limit slightly for search to find relevant results
                $limit = $search ? 500 : 200;
                $logs = $this->systemService->getLogs($limit, $selectedTopic, $search);
            }
        } catch (Exception $e) {
            // Empty logs on error
        }

        return view('netfusion.reports.logs', compact('logs', 'topics', 'selectedTopic', 'search'));
    }
}
