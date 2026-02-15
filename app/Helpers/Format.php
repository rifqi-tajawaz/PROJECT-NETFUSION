<?php

namespace App\Helpers;

class Format
{
    /**
     * Format bytes to human readable string
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public static function bytes($bytes, $precision = 2)
    {
        if ($bytes == 0)
            return '0B';

        $base = log($bytes, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}
