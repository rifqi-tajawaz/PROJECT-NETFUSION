<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NetFusion\Modules\SystemRouterService;
use Illuminate\Support\Facades\Session;
use Exception;

class DhcpController extends Controller
{
    protected $systemService;

    public function __construct(SystemRouterService $systemService)
    {
        $this->systemService = $systemService;
    }

    /**
     * Display DHCP Leases
     */
    public function index()
    {
        $leases = [];
        try {
            if (Session::has('router_session')) {
                $leases = $this->systemService->getDhcpLeases();
            }
        } catch (Exception $e) {
            return redirect()->route('mikrotik-suite.netfusion.settings.index')
                ->with('error', 'Connection error: ' . $e->getMessage());
        }

        return view('netfusion.dhcp.leases', compact('leases'));
    }

    /**
     * Make Lease Static
     */
    public function makeStatic($id)
    {
        try {
            $this->systemService->makeDhcpStatic($id);
            return back()->with('success', 'DHCP Lease made static successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to make static: ' . $e->getMessage());
        }
    }

    /**
     * Remove Lease
     */
    public function destroy($id)
    {
        try {
            $this->systemService->removeDhcpLease($id);
            return back()->with('success', 'DHCP Lease removed successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to remove lease: ' . $e->getMessage());
        }
    }
}
