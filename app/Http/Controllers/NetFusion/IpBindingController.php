<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use App\Services\NetFusion\Modules\HotspotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class IpBindingController extends Controller
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

            $bindings = $this->hotspotService->getIpBindings();
            $servers = $this->hotspotService->getServers(); // For dropdown if needed

            return view('netfusion.ip_binding.index', compact('bindings', 'servers'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to fetch IP Bindings: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'mac_address' => 'required',
            'type' => 'required|in:regular,bypassed,blocked',
        ]);

        try {
            $data = [
                'mac-address' => $request->mac_address,
                'address' => $request->address,
                'to-address' => $request->to_address,
                'server' => $request->server ?? 'all',
                'type' => $request->type,
                'comment' => $request->comment
            ];

            // Remove null values
            $data = array_filter($data, function ($value) {
                return !is_null($value); });

            $this->hotspotService->addIpBinding($data);
            return back()->with('success', 'IP Binding added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to add IP Binding: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'mac_address' => 'required',
            'type' => 'required|in:regular,bypassed,blocked',
        ]);

        try {
            $data = [
                'mac-address' => $request->mac_address,
                'address' => $request->address,
                'to-address' => $request->to_address,
                'server' => $request->server ?? 'all',
                'type' => $request->type,
                'comment' => $request->comment
            ];
            // Remove null values
            $data = array_filter($data, function ($value) {
                return !is_null($value); });

            $this->hotspotService->updateIpBinding($id, $data);
            return back()->with('success', 'IP Binding updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update IP Binding: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->hotspotService->removeIpBinding($id);
            return back()->with('success', 'IP Binding removed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove IP Binding: ' . $e->getMessage());
        }
    }

    public function enable(Request $request, $id)
    {
        try {
            $this->hotspotService->toggleIpBinding($id, true);
            return back()->with('success', 'IP Binding enabled.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to enable IP Binding: ' . $e->getMessage());
        }
    }

    public function disable(Request $request, $id)
    {
        try {
            $this->hotspotService->toggleIpBinding($id, false);
            return back()->with('success', 'IP Binding disabled.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to disable IP Binding: ' . $e->getMessage());
        }
    }
}
