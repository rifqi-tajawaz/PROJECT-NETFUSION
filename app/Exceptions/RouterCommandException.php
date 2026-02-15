<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Router Command Exception
 *
 * Dilempar ketika command yang dikirim ke MikroTik router gagal.
 * Exception ini memberikan response yang user-friendly dengan detail error.
 */
class RouterCommandException extends Exception
{
    /**
     * Command yang menyebabkan error
     *
     * @var string|null
     */
    protected ?string $command = null;

    /**
     * Response dari router
     *
     * @var string|array|null
     */
    protected $routerResponse = null;

    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @param  string|null  $command
     * @param  string|array|null  $routerResponse
     * @param  int  $code
     * @param  \Throwable|null  $previous
     */
    public function __construct(
        string $message = "Router command failed",
        ?string $command = null,
        $routerResponse = null,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->command = $command;
        $this->routerResponse = $routerResponse;
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request): JsonResponse
    {
        $response = [
            'error' => 'router_command_failed',
            'message' => $this->getMessage(),
        ];

        // Sertakan command info untuk admin (dengan permission)
        if ($request->user()?->can('view debug information')) {
            $response['command'] = $this->command;
            $response['router_response'] = $this->routerResponse;
        }

        $response['suggestion'] = 'Please check the command syntax and router configuration.';

        return response()->json($response, 500);
    }

    /**
     * Get the command that failed.
     */
    public function getCommand(): ?string
    {
        return $this->command;
    }

    /**
     * Get the router response.
     *
     * @return string|array|null
     */
    public function getRouterResponse()
    {
        return $this->routerResponse;
    }
}
