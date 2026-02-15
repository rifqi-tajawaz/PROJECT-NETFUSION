<?php

namespace App\Services\MikrotikSuite\Helpers;

/**
 * Service for formatting and beautifying Mikrotik scripts
 */
class ScriptFormatterService
{
    /**
     * Format script with proper indentation and comments
     *
     * @param string $script Raw script
     * @return string Formatted script
     */
    public function format(string $script): string
    {
        $lines = explode("\n", $script);
        $formatted = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);

            // Skip empty lines
            if (empty($trimmed)) {
                $formatted[] = '';
                continue;
            }

            // Add proper indentation for 'add' commands
            if (str_starts_with($trimmed, 'add ')) {
                $formatted[] = '  ' . $trimmed;
            } else {
                $formatted[] = $trimmed;
            }
        }

        return implode("\n", $formatted);
    }

    /**
     * Add header comment to script
     *
     * @param string $script Script content
     * @param string $title Script title
     * @param array $metadata Additional metadata
     * @return string Script with header
     */
    public function addHeader(string $script, string $title, array $metadata = []): string
    {
        $header = "# ====================================\n";
        $header .= "# {$title}\n";
        $header .= "# Generated: " . date('Y-m-d H:i:s') . "\n";

        foreach ($metadata as $key => $value) {
            $header .= "# {$key}: {$value}\n";
        }

        $header .= "# ====================================\n\n";

        return $header . $script;
    }

    /**
     * Remove duplicate lines from script
     *
     * @param string $script Script content
     * @return string Cleaned script
     */
    public function removeDuplicates(string $script): string
    {
        $lines = explode("\n", $script);
        $unique = array_unique($lines);

        return implode("\n", $unique);
    }

    /**
     * Validate script syntax (basic validation)
     *
     * @param string $script Script content
     * @return array Validation errors
     */
    public function validate(string $script): array
    {
        $errors = [];
        $lines = explode("\n", $script);

        foreach ($lines as $lineNum => $line) {
            $trimmed = trim($line);

            // Skip empty lines and comments
            if (empty($trimmed) || str_starts_with($trimmed, '#')) {
                continue;
            }

            // Check for common syntax errors
            if (str_contains($trimmed, '==')) {
                $errors[] = "Line " . ($lineNum + 1) . ": Use single '=' instead of '=='";
            }

            // Check for unclosed quotes
            if (substr_count($trimmed, '"') % 2 !== 0) {
                $errors[] = "Line " . ($lineNum + 1) . ": Unclosed quote";
            }
        }

        return $errors;
    }

    /**
     * Convert script to downloadable format
     *
     * @param string $script Script content
     * @param string $filename Filename
     * @return array File data
     */
    public function toDownloadable(string $script, string $filename = 'mikrotik-config.rsc'): array
    {
        return [
            'content' => $script,
            'filename' => $filename,
            'mime_type' => 'text/plain',
            'size' => strlen($script)
        ];
    }
}
