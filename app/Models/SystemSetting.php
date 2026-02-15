<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'group',
    ];

    /**
     * Get a setting value by key.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("system_setting_{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return static::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public static function set(string $key, $value): void
    {
        $setting = static::where('key', $key)->first();

        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            static::create([
                'key' => $key,
                'value' => $value,
                'type' => gettype($value),
            ]);
        }

        // Clear cache
        Cache::forget("system_setting_{$key}");
    }

    /**
     * Cast value based on type.
     *
     * @param  mixed  $value
     * @param  string  $type
     * @return mixed
     */
    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'double', 'float' => (float) $value,
            'json', 'array' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Check if registration is allowed.
     *
     * @return bool
     */
    public static function allowRegistration(): bool
    {
        return (bool) static::get('allow_registration', true);
    }

    /**
     * Check if email verification is required.
     *
     * @return bool
     */
    public static function requireEmailVerification(): bool
    {
        return (bool) static::get('require_email_verification', true);
    }

    /**
     * Check if site is in maintenance mode.
     *
     * @return bool
     */
    public static function maintenanceMode(): bool
    {
        return (bool) static::get('maintenance_mode', false);
    }

    /**
     * Clear all settings cache.
     *
     * @return void
     */
    public static function clearCache(): void
    {
        $keys = static::pluck('key');

        foreach ($keys as $key) {
            Cache::forget("system_setting_{$key}");
        }
    }
}
