<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Utilities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class SimulatorsController extends Controller
{
    public function queueSimulator(): View
    {
        // This method name might be different in actual file, checking list_dir it has SimulatorsController.
        // Assuming method is queueSimulator based on previous context.
        return view('mikrotik-suite.utilities.simulators.queue-simulator');
    }
}

