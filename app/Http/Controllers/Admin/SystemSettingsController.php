<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SystemSettingsController extends Controller
{
    /**
     * Update system setting.
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'key' => ['required', 'string'],
                'value' => ['required'],
            ]);

            $key = $validated['key'];
            $value = $validated['value'];

            // Convert string boolean to actual boolean
            if ($value === 'true' || $value === '1' || $value === 1) {
                $value = 'true';
            } elseif ($value === 'false' || $value === '0' || $value === 0) {
                $value = 'false';
            }

            SystemSetting::set($key, $value);

            // Log the change
            Log::info('System setting updated', [
                'admin_id' => auth()->id(),
                'key' => $key,
                'value' => $value,
            ]);

            return back()
                ->with('success', "System setting updated successfully.");

        } catch (\Exception $e) {
            Log::error('Failed to update system setting', [
                'admin_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update setting: ' . $e->getMessage());
        }
    }

    /**
     * Toggle registration status.
     */
    public function toggleRegistration(Request $request)
    {
        try {
            $currentStatus = SystemSetting::allowRegistration();
            $newStatus = !$currentStatus;

            SystemSetting::set('allow_registration', $newStatus ? 'true' : 'false');

            // Log the change
            Log::info('Registration status toggled', [
                'admin_id' => auth()->id(),
                'old_status' => $currentStatus ? 'enabled' : 'disabled',
                'new_status' => $newStatus ? 'enabled' : 'disabled',
            ]);

            $message = $newStatus
                ? 'User registration has been ENABLED. New users can now register.'
                : 'User registration has been DISABLED. Only admins can create new users.';

            return back()
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Failed to toggle registration status', [
                'admin_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return back()
                ->with('error', 'Failed to update registration status: ' . $e->getMessage());
        }
    }
}
