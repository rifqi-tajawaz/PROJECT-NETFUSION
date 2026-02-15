<?php

namespace App\Services\Payment;

use App\Models\User;
use Carbon\Carbon;

class SubscriptionService
{
    /**
     * Activate or extend user membership.
     */
    public function activateMembership(User $user, string $packageName, string $duration): void
    {
        // Calculate Expiry
        $currentExpire = $user->membership_expire ? Carbon::parse($user->membership_expire) : Carbon::now();
        if ($currentExpire->isPast()) {
            $currentExpire = Carbon::now();
        }

        $newExpire = $duration === 'yearly'
            ? $currentExpire->addYear()
            : $currentExpire->addMonth();

        $user->update([
            'membership_status' => 'active',
            'membership_package' => $packageName,
            'membership_pay_date' => Carbon::now(),
            'membership_expire' => $newExpire,
        ]);
    }

    public function activateTrial(User $user): void
    {
        $user->update([
            'membership_status' => 'active',
            'membership_package' => 'Premium', // Give full access
            'membership_expire' => Carbon::now()->addDays(3),
            'trial_used_at' => Carbon::now(),
        ]);
    }
}
