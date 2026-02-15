<?php

declare(strict_types=1);

namespace App\Http\Controllers\MikrotikSuite\Utilities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class BatchOperationsController extends Controller
{
    public function batchBackup(): View
    {
        return view('mikrotik-suite.utilities.batch-operations.index');
    }

    public function batchDnsPing(): View
    {
        return view('mikrotik-suite.utilities.batch-operations.index');
    }

    public function batchPortScanner(): View
    {
        return view('mikrotik-suite.utilities.batch-operations.index');
    }

    public function batchSessionRestore(): View
    {
        return view('mikrotik-suite.utilities.batch-operations.index');
    }
}

