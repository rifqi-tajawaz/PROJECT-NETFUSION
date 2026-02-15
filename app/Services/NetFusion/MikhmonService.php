<?php

namespace App\Services\NetFusion;

class MikhmonService
{
    /**
     * Generate random string based on Mikhmon logic.
     * 
     * @param int $length
     * @param string $charType lower, upper, upplow, mix, mix1, mix2, num
     * @return string
     */
    public function generateRandomString(int $length, string $charType): string
    {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '1234567890';

        $characters = '';

        switch ($charType) {
            case 'lower':
                $characters = $lowercase;
                break;
            case 'upper':
                $characters = $uppercase;
                break;
            case 'upplow':
                $characters = $lowercase . $uppercase;
                break;
            case 'mix': // lower + num
                $characters = $lowercase . $numbers;
                break;
            case 'mix1': // upper + num
                $characters = $uppercase . $numbers;
                break;
            case 'mix2': // upper + lower + num
                $characters = $lowercase . $uppercase . $numbers;
                break;
            case 'num':
                $characters = $numbers;
                break;
            default:
                $characters = $lowercase . $numbers;
        }

        $randomString = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $max)];
        }

        return $randomString;
    }

    /**
     * Format comment for tracking (Mikhmon style).
     * Format: user-rand-date-comment
     */
    public function formatComment(string $userMode, string $comment): string
    {
        $rand = rand(100, 999);
        $date = date('m.d.y');
        // Clean comment from pipe characters used as separator
        $cleanComment = str_replace('|', '', $comment);

        return "{$userMode}-{$rand}-{$date}-{$cleanComment}";
    }

    /**
     * Convert value to bytes based on unit (MB/GB).
     */
    public function convertToBytes(int $value, string $unit): int
    {
        switch ($unit) {
            case 'MB':
                return $value * 1048576; // 1024 * 1024
            case 'GB':
                return $value * 1073741824; // 1024 * 1024 * 1024
            default:
                return $value;
        }
    }
}
