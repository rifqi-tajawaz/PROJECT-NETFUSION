<?php

declare(strict_types=1);

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use App\Http\Requests\NetFusion\StoreHotspotUserRequest;
use App\Http\Requests\NetFusion\UpdateHotspotUserRequest;
use App\Jobs\ProcessHotspotBatch;
use App\Services\NetFusion\Modules\HotspotService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class HotspotUserController extends Controller
{
    public function __construct(
        protected HotspotService $hotspotService
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $users = [];
        $profiles = [];
        $stats = [
            'totalCount' => 0,
            'onlineCount' => 0,
            'expiredCount' => 0,
            'disabledCount' => 0,
            'uniqueComments' => [],
        ];
        
        $selectedProfile = $request->profile ?? null;
        $selectedComment = $request->comment ?? null;
        $showExpired = $request->filled('status') && $request->status === 'expired';

        try {
            if (Session::has('router_session')) {
                // Fetch profiles for filter
                $profiles = $this->hotspotService->getProfiles();

                // Get all users
                $allUsers = $this->hotspotService->getUsers();
                $activeUsers = $this->hotspotService->getActiveUsers();

                // Process Stats and Filter
                $result = $this->hotspotService->processStatsAndFilter($allUsers, $activeUsers, $request->all());
                
                $users = $result['users'];
                $stats = $result['stats'];
            }
        } catch (Exception $e) {
            return back()->with('error', 'Connection Error: ' . $e->getMessage());
        }

        return view('netfusion.users.index', array_merge([
            'users' => $users,
            'profiles' => $profiles,
            'selectedProfile' => $selectedProfile,
            'selectedComment' => $selectedComment,
            'showExpired' => $showExpired,
            'bulkResetMsg' => __('netfusion.confirm_reset'),
        ], $stats));
    }

    public function create(): View
    {
        $profiles = [];
        $servers = [];
        try {
            if (Session::has('router_session')) {
                $profiles = $this->hotspotService->getProfiles();
                $servers = $this->hotspotService->getServers();
            }
        } catch (Exception $e) {
        }
        return view('netfusion.users.add', compact('profiles', 'servers'));
    }

    public function store(StoreHotspotUserRequest $request): RedirectResponse
    {
        try {
            $data = [
                'name' => $request->username,
                'password' => $request->password,
                'profile' => $request->profile,
            ];
            
            if ($request->filled('server'))
                $data['server'] = $request->input('server');

            if ($request->filled('comment'))
                $data['comment'] = $request->comment;

            if ($request->filled('limit_uptime'))
                $data['limit-uptime'] = $request->limit_uptime;

            if ($request->filled('limit_bytes_total')) {
                $data['limit-bytes-total'] = $this->convertToBytes((int) $request->limit_bytes_total, $request->limit_bytes_unit ?? 'MB');
            }

            $this->hotspotService->addUser($data);

            return back()->with('success', 'User ' . $data['name'] . ' created successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed: ' . $e->getMessage())->withInput();
        }
    }

    public function generate(): View
    {
        $profiles = [];
        $servers = [];
        try {
            if (Session::has('router_session')) {
                $profiles = $this->hotspotService->getProfiles();
                $servers = $this->hotspotService->getServers();
            }
        } catch (Exception $e) {
        }
        return view('netfusion.users.generate', compact('profiles', 'servers'));
    }

    public function storeBatch(Request $request): RedirectResponse
    {
        $request->validate([
            'qty' => 'required|integer|min:1|max:500',
            'server' => 'required|string',
            'profile' => 'required|string',
            'prefix' => 'required|string'
        ]);

        try {
            // Dispatch Job
            ProcessHotspotBatch::dispatch(
                $request->all(),
                (int) $request->qty,
                Session::get('router_session'),
                Auth::user()
            );

            return redirect()->route('mikrotik-suite.netfusion.users.index')
                ->with('success', "Batch generation for {$request->qty} users started in background.");

        } catch (Exception $e) {
            return back()->with('error', 'Batch failed: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(string $id): View|RedirectResponse
    {
        $user = null;
        $profiles = [];
        $servers = [];
        try {
            if (Session::has('router_session')) {
                $users = $this->hotspotService->getUsers(['.id' => $id]);
                $user = $users[0] ?? null;

                $profiles = $this->hotspotService->getProfiles();
                $servers = $this->hotspotService->getServers();
            }
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        if (!$user)
            return back()->with('error', 'User not found.');
        return view('netfusion.users.edit', compact('user', 'profiles', 'servers'));
    }

    public function update(UpdateHotspotUserRequest $request, string $id): RedirectResponse
    {
        try {
            $updateData = ['profile' => $request->profile];
            if ($request->filled('password'))
                $updateData['password'] = $request->password;

            if ($request->filled('server'))
                $updateData['server'] = $request->input('server');

            if ($request->filled('comment'))
                $updateData['comment'] = $request->comment;

            $updateData['limit-uptime'] = $request->limit_uptime ?? '';

            if ($request->filled('limit_bytes_total')) {
                $updateData['limit-bytes-total'] = $this->convertToBytes((int) $request->limit_bytes_total, $request->limit_bytes_unit ?? 'MB');
            } else {
                $updateData['limit-bytes-total'] = '';
            }

            $this->hotspotService->updateUser($id, $updateData);
            return redirect()->route('mikrotik-suite.netfusion.users.index')->with('success', 'User updated');
        } catch (Exception $e) {
            return back()->with('error', 'Update Failed: ' . $e->getMessage());
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->hotspotService->removeUser($id);
            return redirect()->route('mikrotik-suite.netfusion.users.index')->with('success', 'User deleted');
        } catch (Exception $e) {
            return back()->with('error', 'Delete Failed: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|string|in:delete,disable,enable,reset'
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $count = 0;
        $errors = 0;

        // Increase time limit for batch operations (Still sync for now, can be queued later)
        set_time_limit(300);

        try {
            foreach ($ids as $id) {
                try {
                    match ($action) {
                        'delete' => $this->hotspotService->removeUser($id),
                        'disable' => $this->hotspotService->toggleUser($id, false),
                        'enable' => $this->hotspotService->toggleUser($id, true),
                        'reset' => $this->hotspotService->resetCounters($id),
                        default => throw new Exception('Invalid action'),
                    };
                    $count++;
                } catch (Exception $e) {
                    $errors++;
                }
            }

            $msg = "Action ($action) applied to $count users.";
            if ($errors > 0)
                $msg .= " ($errors failed)";

            return back()->with('success', $msg);
        } catch (Exception $e) {
            return back()->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }

    public function destroyByComment(Request $request): RedirectResponse
    {
        $request->validate(['comment' => 'required|string']);
        $comment = $request->comment;

        try {
            // Optimally we should use filter on getUsers if possible, but existing service caches result
            $allUsers = $this->hotspotService->getUsers();
            $idsToDelete = [];

            foreach ($allUsers as $u) {
                if (isset($u['comment']) && $u['comment'] === $comment) {
                    $idsToDelete[] = $u['.id'];
                }
            }

            // Batch delete
            foreach ($idsToDelete as $id) {
                $this->hotspotService->removeUser($id);
            }

            return redirect()->route('mikrotik-suite.netfusion.users.index')->with('success', 'Deleted ' . count($idsToDelete) . ' users with comment: ' . $comment);

        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete batch: ' . $e->getMessage());
        }
    }

    public function batches(): View
    {
        $batches = [];

        try {
            if (Session::has('router_session')) {
                $users = $this->hotspotService->getUsers();

                // Group users by comment
                $grouped = [];
                foreach ($users as $user) {
                    $comment = $user['comment'] ?? null;

                    if (!$comment)
                        continue;

                    if (!isset($grouped[$comment])) {
                        $grouped[$comment] = [
                            'name' => $comment,
                            'comment' => $comment,
                            'count' => 0,
                            'profile' => $user['profile'] ?? 'Unknown',
                            'created_at' => '-',
                            'server' => $user['server'] ?? 'all'
                        ];
                    }
                    $grouped[$comment]['count']++;

                    // Update profile if unknown
                    if ($grouped[$comment]['profile'] === 'Unknown' && isset($user['profile'])) {
                        $grouped[$comment]['profile'] = $user['profile'];
                    }
                }

                // Sort by name
                ksort($grouped);
                $batches = array_values($grouped);
            }
        } catch (Exception $e) {
            // Log error
        }

        return view('netfusion.users.batches', compact('batches'));
    }

    private function convertToBytes(int $value, string $unit): int
    {
        return match ($unit) {
            'GB' => $value * 1024 * 1024 * 1024,
            'MB' => $value * 1024 * 1024,
            'KB' => $value * 1024,
            default => $value,
        };
    }
}
