<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NetFusion\Modules\HotspotService;
use Illuminate\Support\Facades\Session;
use Exception;

class UserProfileController extends Controller
{
    protected $hotspotService;

    public function __construct(HotspotService $hotspotService)
    {
        $this->hotspotService = $hotspotService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles = [];
        $profilePrices = [];

        try {
            if (Session::has('router_session')) {
                $profiles = $this->hotspotService->getProfiles();

                // Get price info for each profile
                foreach ($profiles as $profile) {
                    if (isset($profile['name'])) {
                        $profilePrices[$profile['name']] = $this->hotspotService->getProfilePriceInfo($profile['name']);
                    }
                }
            }
        } catch (Exception $e) {
            return redirect()->route('mikrotik-suite.netfusion.settings.index')
                ->with('error', 'Connection error: ' . $e->getMessage());
        }

        return view('netfusion.profiles.index', compact('profiles', 'profilePrices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('netfusion.profiles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:50',
            'shared_users' => 'nullable|integer|min:1',
            'rate_limit' => 'nullable|string',
        ]);

        try {
            if (!Session::has('router_session')) {
                return back()->with('error', 'No active RouterOS session.');
            }

            $data = [
                'name' => $request->name,
            ];

            if ($request->filled('shared_users')) {
                $data['shared-users'] = $request->shared_users;
            }

            if ($request->filled('rate_limit')) {
                $data['rate-limit'] = $request->rate_limit;
            }

            // Defaults for better UX
            $data['status-autorefresh'] = '1m';
            $data['transparent-proxy'] = 'no';

            $this->hotspotService->addProfile($data);

            return back()->with('success', 'Profile created successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to create profile: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            if (!Session::has('router_session')) {
                return redirect()->route('mikrotik-suite.netfusion.profiles.index')->with('error', 'No active session.');
            }

            $profile = $this->hotspotService->getProfile($id);

            if (!$profile) {
                return redirect()->route('mikrotik-suite.netfusion.profiles.index')->with('error', 'Profile not found.');
            }

            return view('netfusion.profiles.edit', compact('profile'));
        } catch (Exception $e) {
            return back()->with('error', 'Error fetching profile: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:50',
            'shared_users' => 'nullable|integer|min:1',
            'rate_limit' => 'nullable|string',
        ]);

        try {
            $data = [
                'name' => $request->name,
            ];

            if ($request->filled('shared_users')) {
                $data['shared-users'] = $request->shared_users;
            } else {
                $data['shared-users'] = '1'; // Default
            }

            if ($request->filled('rate_limit')) {
                $data['rate-limit'] = $request->rate_limit;
            }

            if ($request->filled('address_pool')) {
                $data['address-pool'] = $request->address_pool;
            }

            $this->hotspotService->updateProfile($id, $data);

            return redirect()->route('mikrotik-suite.netfusion.profiles.index')->with('success', 'Profile updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update profile: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            if (!Session::has('router_session')) {
                return back()->with('error', 'No active RouterOS session.');
            }

            $this->hotspotService->removeProfile($id);

            return back()->with('success', 'Profile deleted successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete profile: ' . $e->getMessage());
        }
    }
}
