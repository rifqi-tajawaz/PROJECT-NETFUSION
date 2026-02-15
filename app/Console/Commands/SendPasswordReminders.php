<?php

namespace App\Console\Commands;

use App\Mail\PasswordExpiringSoon;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Send Password Expiry Reminders
 *
 * This command sends email notifications to users whose passwords
 * are about to expire. Should be run daily via scheduler.
 */
class SendPasswordReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send password expiry reminder emails to users';

    /**
     * The number of days before expiration to send reminders.
     *
     * @var array
     */
    protected $reminderDays = [7, 3, 1];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Sending password expiry reminders...');
        $totalSent = 0;
        $totalFailed = 0;

        foreach ($this->reminderDays as $days) {
            $this->info("Checking users with passwords expiring in {$days} day(s)...");

            $users = $this->getUsersWithExpiringPasswords($days);

            $this->info("Found {$users->count()} user(s).");

            foreach ($users as $user) {
                try {
                    Mail::to($user->email)->send(new PasswordExpiringSoon($days));

                    $this->info("✓ Sent reminder to {$user->email} ({$days} days remaining)");

                    // Log the reminder
                    Log::info('Password expiry reminder sent', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'days_remaining' => $days,
                    ]);

                    $totalSent++;
                } catch (\Exception $e) {
                    $this->error("✗ Failed to send to {$user->email}: {$e->getMessage()}");

                    Log::error('Failed to send password expiry reminder', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'error' => $e->getMessage(),
                    ]);

                    $totalFailed++;
                }
            }

            $this->newLine();
        }

        $this->info("--------------------------------------------------");
        $this->info("Total reminders sent: {$totalSent}");
        $this->info("Total failed: {$totalFailed}");

        Log::info('Password reminder batch completed', [
            'total_sent' => $totalSent,
            'total_failed' => $totalFailed,
        ]);

        return self::SUCCESS;
    }

    /**
     * Get users whose passwords will expire in the specified number of days.
     *
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getUsersWithExpiringPasswords(int $days)
    {
        $today = now()->startOfDay();
        $expiryDate = now()->addDays($days)->startOfDay();

        return User::query()
            ->where('is_active', true)
            ->whereNotNull('password_expires_at')
            ->whereDate('password_expires_at', '=', $expiryDate)
            // Don't send to users who must change password immediately (already notified)
            ->where('must_change_password', false)
            ->get();
    }
}
