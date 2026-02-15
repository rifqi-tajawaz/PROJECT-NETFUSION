<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\NetFusion\StorePppSecretRequest;
use App\Services\NetFusion\Modules\PppService;
use Illuminate\Support\Facades\Session;
use Exception;

class PppController extends Controller
{
    protected $pppService;

    public function __construct(PppService $pppService)
    {
        $this->pppService = $pppService;
    }

    /**
     * Display PPP Secrets (Users)
     */
    public function secrets()
    {
        $secrets = [];
        $profiles = [];
        try {
            if (Session::has('router_session')) {
                $secrets = $this->pppService->getSecrets();
                $profiles = $this->pppService->getProfiles();
            }
        } catch (Exception $e) {
            return redirect()->route('mikrotik-suite.netfusion.settings.index')
                ->with('error', 'Connection error: ' . $e->getMessage());
        }

        return view('netfusion.ppp.secrets', compact('secrets', 'profiles'));
    }

    /**
     * Store new PPP Secret
     */
    public function storeSecret(StorePppSecretRequest $request)
    {
        try {
            $data = $request->validated();

            // Map request keys to RouterOS keys if needed
            // Controller logic: clean up data mapping
            $apiData = [
                'name' => $data['name'],
                'password' => $data['password'],
                'profile' => $data['profile'],
                'service' => $data['service'],
                'comment' => $data['comment'] ?? '',
            ];

            if ($request->filled('local_address'))
                $apiData['local-address'] = $data['local_address'];
            if ($request->filled('remote_address'))
                $apiData['remote-address'] = $data['remote_address'];

            $this->pppService->addSecret($apiData);

            return back()->with('success', 'PPP Secret created successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to create secret: ' . $e->getMessage());
        }
    }

    /**
     * Delete PPP Secret
     */
    public function destroySecret($id)
    {
        try {
            $this->pppService->removeSecret($id);
            return back()->with('success', 'PPP Secret deleted successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete secret: ' . $e->getMessage());
        }
    }

    /**
     * Display PPP Profiles
     */
    public function profiles()
    {
        $profiles = [];
        try {
            if (Session::has('router_session')) {
                $profiles = $this->pppService->getProfiles();
            }
        } catch (Exception $e) {
            // handle error
        }

        return view('netfusion.ppp.profiles', compact('profiles'));
    }

    public function storeProfile(Request $request)
    {
        // Ideally Create StorePppProfileRequest
        $request->validate(['name' => 'required|string']);

        try {
            $data = [
                'name' => $request->name,
                'local-address' => $request->local_address,
                'remote-address' => $request->remote_address,
                'rate-limit' => $request->rate_limit,
                'dns-server' => $request->dns_server,
            ];
            // Filter nulls
            $data = array_filter($data);

            $this->pppService->addProfile($data);
            return back()->with('success', 'Profile created successfully');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroyProfile($id)
    {
        try {
            $this->pppService->removeProfile($id);
            return back()->with('success', 'Profile deleted');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display Active Connections
     */
    public function active()
    {
        $active = [];
        try {
            if (Session::has('router_session')) {
                $active = $this->pppService->getActive();
            }
        } catch (Exception $e) {
            // handle error
        }
        return view('netfusion.ppp.active', compact('active'));
    }

    /**
     * Disconnect Active Connection
     */
    public function disconnect($id)
    {
        try {
            $this->pppService->removeActive($id);
            return back()->with('success', 'Connection terminated successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to disconnect: ' . $e->getMessage());
        }
    }
}
