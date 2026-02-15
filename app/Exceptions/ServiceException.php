<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Service Exception
 *
 * Base exception untuk semua service-related exceptions.
 * Exception ini menyediakan response yang konsisten untuk error di service layer.
 */
class ServiceException extends Exception
{
    /**
     * Error code untuk response
     *
     * @var string
     */
    protected string $errorCode = 'service_error';

    /**
     * HTTP status code
     *
     * @var int
     */
    protected int $httpStatusCode = 500;

    /**
     * Additional context data
     *
     * @var array
     */
    protected array $context = [];

    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @param  string  $errorCode
     * @param  int  $httpStatusCode
     * @param  array  $context
     * @param  int  $code
     * @param  \Throwable|null  $previous
     */
    public function __construct(
        string $message = "Service operation failed",
        string $errorCode = 'service_error',
        int $httpStatusCode = 500,
        array $context = [],
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
        $this->httpStatusCode = $httpStatusCode;
        $this->context = $context;
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
            'error' => $this->errorCode,
            'message' => $this->getMessage(),
        ];

        // Sertakan context jika ada
        if (!empty($this->context)) {
            $response['context'] = $this->context;
        }

        // Sertakan stack trace hanya di development
        if (config('app.debug')) {
            $response['exception'] = get_class($this);
            $response['trace'] = collect($this->getTrace())->take(5)->toArray();
        }

        return response()->json($response, $this->httpStatusCode);
    }

    /**
     * Get the error code.
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * Get the HTTP status code.
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    /**
     * Get the context data.
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
