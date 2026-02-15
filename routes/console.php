<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule password expiry reminders to run daily at 9:00 AM
Schedule::command('password:send-reminders')
    ->dailyAt('09:00')
    ->description('Send password expiry reminder emails')
    ->onSuccess(function () {
        \Log::info('Password reminders sent successfully');
    })
    ->onFailure(function () {
        \Log::error('Failed to send password reminders');
    });
