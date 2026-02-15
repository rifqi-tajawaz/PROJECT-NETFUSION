<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use App\Services\NetFusion\Modules\HotspotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CookieController extends Controller
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

            $cookies = $this->hotspotService->getCookies();
            return view('netfusion.cookies.index', compact('cookies'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to fetch cookies: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->hotspotService->removeCookie($id);
            return back()->with('success', 'Cookie removed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove cookie: ' . $e->getMessage());
        }
    }
}
