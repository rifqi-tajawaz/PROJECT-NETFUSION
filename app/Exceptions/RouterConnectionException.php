<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Router Connection Exception
 *
 * Dilempar ketika gagal terkoneksi ke MikroTik router.
 * Exception ini memberikan response yang user-friendly.
 */
class RouterConnectionException extends Exception
{
    /**
     * Router IP address
     *
     * @var string|null
     */
    protected ?string $routerIp = null;

    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @param  string|null  $routerIp
     * @param  int  $code
     * @param  \Throwable|null  $previous
     */
    public function __construct(string $message = "Failed to connect to router", ?string $routerIp = null, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->routerIp = $routerIp;
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request): JsonResponse
    {
        return response()->json([
            'error' => 'router_connection_failed',
            'message' => $this->getMessage(),
            'router_ip' => $this->routerIp,
            'suggestion' => 'Please check if the router is online and reachable from this server.',
        ], 503);
    }

    /**
     * Get the router IP.
     */
    public function getRouterIp(): ?string
    {
        return $this->routerIp;
    }
}
