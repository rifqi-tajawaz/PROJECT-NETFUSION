<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NetFusion\RouterOSService;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    protected $routerOSService;
    protected $storageFile = 'NetFusion_sessions.json';

    public function __construct(RouterOSService $routerOSService)
    {
        $this->routerOSService = $routerOSService;
    }

    public function index()
    {
        // Load sessions from JSON storage
        $sessions = [];
        if (Storage::exists($this->storageFile)) {
            $sessions = json_decode(Storage::get($this->storageFile), true) ?? [];
        }

        $currentSession = Session::get('router_session');

        // Mock Admin Settings (In a real app, this might come from DB or Config)
        // For now we just pass an empty structure or current user info if needed
        $adminSettings = [
            'quick_print' => Session::get('NetFusion_quick_print', 'disable'),
        ];

        return view('netfusion.settings.index', compact('sessions', 'currentSession', 'adminSettings'));
    }

    public function saveSession(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string|max:50',
            'ip' => 'required|string',
            'user' => 'required|string|max:255',
            'password' => $request->id ? 'nullable|string|min:4' : 'required|string|min:4',
            'port' => 'required|integer|between:1,65535',
            'hotspot_name' => 'required|string|max:100',
            'dns_name' => 'required|string|regex:/^[a-zA-Z0-9.-]+$/|max:255',
            'currency' => 'nullable|string|max:10',
        ]);

        $data = $request->except(['_token']);

        // Handle Password: if empty on edit, keep existing. If new, required.
        // For simplicity in this file-based approach, we'll just require it for now or handle merge.

        $sessions = [];
        if (Storage::exists($this->storageFile)) {
            $sessions = json_decode(Storage::get($this->storageFile), true) ?? [];
        }

        // Generate ID if not exists (New Session), using Secure Random String
        $id = $request->id ?? Str::random(16);

        // Prepare Data
        $sessionData = [
            'id' => $id,
            'name' => $data['session_name'],
            'ip' => $data['ip'],
            'user' => $data['user'],
            'port' => $data['port'],
            'hotspot_name' => $data['hotspot_name'],
            'dns_name' => $data['dns_name'],
            'currency' => $data['currency'] ?? 'Rp',
        ];

        // Handle Password with Encryption
        if ($request->filled('password')) {
            $sessionData['password'] = \Illuminate\Support\Facades\Crypt::encryptString($data['password']);
        } elseif (isset($sessions[$id]['password'])) {
            $sessionData['password'] = $sessions[$id]['password']; // Keep existing
        } else {
            return back()->with('error', 'Password is required for new sessions.');
        }

        $sessions[$id] = $sessionData;

        Storage::put($this->storageFile, json_encode($sessions, JSON_PRETTY_PRINT));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Session saved successfully.']);
        }

        return back()->with('success', 'Session saved successfully.');
    }

    public function deleteSession($id)
    {
        if (Storage::exists($this->storageFile)) {
            $sessions = json_decode(Storage::get($this->storageFile), true) ?? [];
            if (isset($sessions[$id])) {
                unset($sessions[$id]);
                Storage::put($this->storageFile, json_encode($sessions, JSON_PRETTY_PRINT));
                return back()->with('success', 'Session deleted successfully.');
            }
        }
        return back()->with('error', 'Session not found.');
    }

    public function connect($id)
    {
        if (Storage::exists($this->storageFile)) {
            $sessions = json_decode(Storage::get($this->storageFile), true) ?? [];
            if (isset($sessions[$id])) {
                $s = $sessions[$id];

                try {
                    // Decrypt Password
                    $password = $s['password'];
                    try {
                        $password = \Illuminate\Support\Facades\Crypt::decryptString($s['password']);
                    } catch (Exception $e) {
                        // Fallback for legacy plain text passwords
                    }

                    // Test Connection (Now triggers real RouterOS query)
                    if ($this->routerOSService->connect($s['ip'], $s['user'], $password, $s['port'])) {
                        // Save to Active Session (We keep the encrypted version in session for security)
                        Session::put('router_session', $s);

                        return redirect()->route('mikrotik-suite.netfusion.dashboard')->with('success', "Connected to {$s['name']}!");
                    } else {
                        return back()->with('error', "Connection failed. Please check IP, User, Password, and API Port (default 8728).");
                    }
                } catch (Exception $e) {
                    return back()->with('error', "Connection error: " . $e->getMessage());
                }
            }
        }
        return back()->with('error', 'Session not found.');
    }

    public function disconnect()
    {
        Session::forget('router_session');
        return redirect()->route('mikrotik-suite.netfusion.settings.index')->with('success', 'Disconnected.');
    }

    /**
     * Upload Logo
     */
    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $session = Session::get('router_session');
        if (!$session) {
            return back()->with('error', 'Please connect to a router session first.');
        }

        if ($request->file('logo')->isValid()) {
            // Mikhmon Standard: logo-{session_name}.png
            // Clean session name to be safe for filenames
            $safeName = Str::slug($session['name']);
            $imageName = 'logo-' . $safeName . '.png';

            $request->logo->move(public_path('images'), $imageName);
            return back()->with('success', "Voucher Logo uploaded successfully for session '{$session['name']}'.");
        }

        return back()->with('error', 'Failed to upload logo.');
    }

    public function pingSession($id)
    {
        if (Storage::exists($this->storageFile)) {
            $sessions = json_decode(Storage::get($this->storageFile), true) ?? [];
            if (isset($sessions[$id])) {
                $s = $sessions[$id];
                $ip = $s['ip'];
                $port = $s['port'];

                // Basic ping (fsockopen) is fine, but we could also try the service connect logic if we wanted deep ping.
                // For now, simple port check is sufficient for "Ping" as requested.
                try {
                    $connection = @fsockopen($ip, $port, $errno, $errstr, 2);
                    if ($connection) {
                        fclose($connection);
                        return back()->with('success', "Ping to {$s['name']} ($ip:$port) successful!");
                    } else {
                        return back()->with('error', "Ping to {$s['name']} failed: $errstr");
                    }
                } catch (Exception $e) {
                    return back()->with('error', "Ping error: " . $e->getMessage());
                }
            }
        }
        return back()->with('error', 'Session not found.');
    }

    public function exportSessions()
    {
        if (Storage::exists($this->storageFile)) {
            return Storage::download($this->storageFile, 'NetFusion_sessions_backup_' . date('Y-m-d_Hs') . '.json');
        }
        return back()->with('error', 'No sessions to export.');
    }

    public function importSessions(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json'
        ]);

        try {
            $content = file_get_contents($request->file('file')->getRealPath());
            $json = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'Invalid JSON file.');
            }

            // Validate structure loosely
            foreach ($json as $s) {
                if (!isset($s['ip']) || !isset($s['user'])) {
                    return back()->with('error', 'Invalid session data in file.');
                }
            }

            Storage::put($this->storageFile, json_encode($json, JSON_PRETTY_PRINT));
            return back()->with('success', 'Sessions imported successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }




}
