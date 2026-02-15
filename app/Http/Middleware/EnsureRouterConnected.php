<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use App\Services\NetFusion\MikhmonAPI;

class EnsureRouterConnected
{
    protected $api;

    public function __construct(MikhmonAPI $api)
    {
        $this->api = $api;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('router_session')) {
            $s = Session::get('router_session');

            // Only connect if not already connected
            if (!$this->api->connected) {
                try {
                    $password = $s['password'];
                    try {
                        $password = Crypt::decryptString($s['password']);
                    } catch (\Exception $e) {
                        // Pass raw if decrypt fails
                    }

                    $this->api->connect($s['ip'], $s['user'], $password, $s['port'] ?? 8728);
                } catch (\Exception $e) {
                    // Log error but allow request to proceed (controller might handle it)
                    \Illuminate\Support\Facades\Log::error('Middleware Connect Error: ' . $e->getMessage());
                }
            }
        }

        return $next($request);
    }
}
