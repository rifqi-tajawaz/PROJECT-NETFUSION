<?php

namespace App\Http\Controllers\NetFusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NetFusion\Modules\HotspotService;

class PrintingController extends Controller
{
    protected $hotspotService;

    public function __construct(HotspotService $hotspotService)
    {
        $this->hotspotService = $hotspotService;
    }

    public function index()
    {
        // Get all users
        $users = $this->hotspotService->getUsers();

        // Get Profiles
        $profiles = $this->hotspotService->getProfiles();

        // Filter valid users (array)
        $users = is_array($users) ? $users : [];
        $profiles = is_array($profiles) ? $profiles : [];

        // Group by Comment to find Batches
        $batches = [];
        foreach ($users as $user) {
            if (isset($user['comment']) && !empty($user['comment'])) {
                $comment = $user['comment'];
                if (!isset($batches[$comment])) {
                    $batches[$comment] = [
                        'name' => $comment,
                        'count' => 0,
                        'profile' => $user['profile'] ?? 'unknown',
                        'users' => []
                    ];
                }
                $batches[$comment]['count']++;
                $batches[$comment]['users'][] = $user;
            }
        }

        // Sort batches by name
        ksort($batches);

        // Check for pre-selected batch from query string
        $selectedBatch = request('batch');

        return view('netfusion.printing.index', compact('batches', 'profiles', 'selectedBatch'));
    }
}
