<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use App\Services\NetFusion\Modules\HotspotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HotspotHostController extends Controller
{
    protected $hotspotService;

    public function __construct(HotspotService $hotspotService)
    {
        $this->hotspotService = $hotspotService;
    }

    public function index()
    {
        try {
            if (!Session::has('router_session')) {
                return redirect()->route('mikrotik-suite.netfusion.settings.index')->with('error', 'Please connect to a router first.');
            }

            $hosts = $this->hotspotService->getHosts();
            return view('netfusion.hosts.index', compact('hosts'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to fetch hosts: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->hotspotService->removeHost($id);
            return back()->with('success', 'Host removed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove host: ' . $e->getMessage());
        }
    }
}
