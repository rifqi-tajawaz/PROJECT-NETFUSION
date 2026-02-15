<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Base Service Class
 *
 * Base class untuk semua service classes.
 * Menyediakan common functionality seperti error handling, logging, dan caching.
 *
 * @package App\Services
 */
abstract class BaseService
{
    /**
     * Log exception dengan context yang lengkap.
     *
     * @param  \Exception  $e
     * @param  string  $context
     * @param  array  $additionalData
     * @return void
     */
    protected function logException(\Exception $e, string $context = '', array $additionalData = []): void
    {
        $logData = array_merge([
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'class' => get_class($e),
            'context' => $context ?: static::class,
        ], $additionalData);

        // Log dengan level yang sesuai
        if ($this->isCriticalException($e)) {
            Log::critical($context ?: 'Service Error', $logData);
        } elseif ($this->isWarningException($e)) {
            Log::warning($context ?: 'Service Warning', $logData);
        } else {
            Log::error($context ?: 'Service Error', $logData);
        }
    }

    /**
     * Handle exception dengan logging dan re-throw.
     *
     * @param  \Exception  $e
     * @param  string  $message
     * @param  string  $errorCode
     * @param  int  $httpStatusCode
     * @param  array  $context
     * @return void
     * @throws \App\Exceptions\ServiceException
     */
    protected function handleException(
        \Exception $e,
        string $message = 'Service operation failed',
        string $errorCode = 'service_error',
        int $httpStatusCode = 500,
        array $context = []
    ): void {
        // Log exception
        $this->logException($e, $message, $context);

        // Re-throw as ServiceException atau biarkan exception original
        if (!($e instanceof ServiceException)) {
            throw new ServiceException(
                $message,
                $errorCode,
                $httpStatusCode,
                array_merge($context, ['original_exception' => get_class($e)]),
                previous: $e
            );
        }

        throw $e;
    }

    /**
     * Execute callback dengan error handling otomatis.
     *
     * @param  callable  $callback
     * @param  string  $errorMessage
     * @param  string  $errorCode
     * @param  int  $httpStatusCode
     * @return mixed
     * @throws \App\Exceptions\ServiceException
     */
    protected function executeWithErrorHandling(
        callable $callback,
        string $errorMessage = 'Operation failed',
        string $errorCode = 'operation_failed',
        int $httpStatusCode = 500
    ) {
        try {
            return $callback();
        } catch (\Exception $e) {
            $this->handleException($e, $errorMessage, $errorCode, $httpStatusCode);
        }
    }

    /**
     * Get value dari cache dengan callback jika tidak ada.
     *
     * @param  string  $key
     * @param  callable  $callback
     * @param  int|null  $ttl
     * @return mixed
     * @throws \App\Exceptions\ServiceException
     */
    protected function remember(string $key, callable $callback, ?int $ttl = null)
    {
        try {
            $value = Cache::get($key);

            if ($value !== null) {
                return $value;
            }

            $value = $callback();

            if ($ttl !== null) {
                Cache::put($key, $value, $ttl);
            } else {
                Cache::forever($key, $value);
            }

            return $value;
        } catch (InvalidArgumentException $e) {
            $this->handleException($e, 'Cache operation failed', 'cache_error', 500);
        }
    }

    /**
     * Clear cache untuk key tertentu.
     *
     * @param  string  $key
     * @return bool
     */
    protected function clearCache(string $key): bool
    {
        try {
            return Cache::forget($key);
        } catch (\Exception $e) {
            $this->logException($e, 'Cache clear failed');
            return false;
        }
    }

    /**
     * Clear multiple cache keys.
     *
     * @param  array  $keys
     * @return void
     */
    protected function clearMultipleCache(array $keys): void
    {
        foreach ($keys as $key) {
            $this->clearCache($key);
        }
    }

    /**
     * Generate cache key dengan prefix.
     *
     * @param  string  $key
     * @param  array  $parameters
     * @return string
     */
    protected function getCacheKey(string $key, array $parameters = []): string
    {
        $prefix = strtolower(str_replace('\\', '.', static::class));
        $paramString = empty($parameters) ? '' : '.' . md5(json_encode($parameters));
        return "{$prefix}.{$key}{$paramString}";
    }

    /**
     * Cek apakah exception adalah critical exception.
     *
     * @param  \Exception  $e
     * @return bool
     */
    protected function isCriticalException(\Exception $e): bool
    {
        // Database connection errors
        if ($e instanceof \Illuminate\Database\QueryException) {
            return true;
        }

        // File system errors
        if ($e instanceof \Illuminate\Contracts\Filesystem\FileNotFoundException) {
            return true;
        }

        return false;
    }

    /**
     * Cek apakah exception adalah warning exception.
     *
     * @param  \Exception  $e
     * @return bool
     */
    protected function isWarningException(\Exception $e): bool
    {
        // Model not found
        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return true;
        }

        // Validation errors
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return true;
        }

        // HTTP not found
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return true;
        }

        return false;
    }

    /**
     * Validate required data.
     *
     * @param  array  $data
     * @param  array  $required
     * @return void
     * @throws \App\Exceptions\ServiceException
     */
    protected function validateRequired(array $data, array $required): void
    {
        $missing = [];

        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === null || $data[$field] === '') {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new ServiceException(
                sprintf('Missing required fields: %s', implode(', ', $missing)),
                'validation_error',
                422,
                ['missing_fields' => $missing]
            );
        }
    }

    /**
     * Sanitize array data (remove null values).
     *
     * @param  array  $data
     * @return array
     */
    protected function sanitizeArray(array $data): array
    {
        return array_filter($data, fn($value) => $value !== null);
    }

    /**
     * Convert bytes to human readable format.
     *
     * @param  int  $bytes
     * @param  int  $precision
     * @return string
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Convert seconds to human readable format.
     *
     * @param  int  $seconds
     * @return string
     */
    protected function formatDuration(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }
}
