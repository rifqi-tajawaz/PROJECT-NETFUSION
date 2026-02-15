<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NetFusion\Modules\SystemRouterService;
use Illuminate\Support\Facades\Session;
use Exception;

class SystemController extends Controller
{
    protected $systemService;

    public function __construct(SystemRouterService $systemService)
    {
        $this->systemService = $systemService;
    }

    /**
     * Display System Dashboard (Resources & Power)
     */
    public function index()
    {
        $resource = [];
        $uptime = '-';
        $identity = 'RouterOS';

        try {
            if (Session::has('router_session')) {
                $resource = $this->systemService->getResources();
                $identity = $this->systemService->getIdentity()['name'] ?? 'RouterOS';
                $uptime = $resource['uptime'] ?? '-';
            }
        } catch (Exception $e) {
            return redirect()->route('mikrotik-suite.netfusion.settings.index')
                ->with('error', 'Connection error: ' . $e->getMessage());
        }

        return view('netfusion.system.index', compact('resource', 'uptime', 'identity'));
    }

    /**
     * System Reboot
     */
    public function reboot()
    {
        try {
            $this->systemService->reboot();
            return back()->with('success', 'System is rebooting...');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to reboot: ' . $e->getMessage());
        }
    }

    /**
     * System Shutdown
     */
    public function shutdown()
    {
        try {
            $this->systemService->shutdown();
            return back()->with('success', 'System is shutting down...');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to shutdown: ' . $e->getMessage());
        }
    }

    /**
     * Display Scheduler List
     */
    public function scheduler()
    {
        $schedulers = [];
        try {
            if (Session::has('router_session')) {
                $schedulers = $this->systemService->getSchedulers();
            }
        } catch (Exception $e) {
            return back()->with('error', 'Connection error: ' . $e->getMessage());
        }

        return view('netfusion.system.scheduler', compact('schedulers'));
    }

    /**
     * Store New Scheduler
     */
    public function storeScheduler(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'interval' => 'required',
            'on_event' => 'required',
        ]);

        try {
            $this->systemService->addScheduler([
                'name' => $request->name,
                'interval' => $request->interval,
                'on-event' => $request->on_event,
                'start-date' => $request->start_date ?? 'Jan/01/1970',
                'start-time' => $request->start_time ?? '00:00:00',
                'comment' => $request->comment ?? '',
            ]);
            return back()->with('success', 'Scheduler added successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to add scheduler: ' . $e->getMessage());
        }
    }

    /**
     * Remove Scheduler
     */
    public function destroyScheduler($id)
    {
        try {
            $this->systemService->removeScheduler($id);
            return back()->with('success', 'Scheduler removed successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to remove: ' . $e->getMessage());
        }
    }

    /**
     * Toggle Scheduler Status
     */
    public function toggleScheduler($id)
    {
        $disable = request()->get('disable') == 'true';
        try {
            $this->systemService->toggleScheduler($id, $disable);
            return back()->with('success', 'Scheduler status updated.');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }
}
