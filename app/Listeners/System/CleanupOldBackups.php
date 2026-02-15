<?php

namespace App\Listeners\System;

use App\Events\System\BackupCompleted;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CleanupOldBackups
{
    /**
     * Handle the event.
     */
    public function handle(BackupCompleted $event): void
    {
        $backupPath = dirname($event->backupPath);
        $maxBackups = config('backup.max_backups', 10);

        // Get all backup files
        $backups = collect(File::files($backupPath))
            ->sortByDesc(function ($file) {
                return $file->getMTime();
            });

        // Delete old backups if we exceed the limit
        if ($backups->count() > $maxBackups) {
            $backups->slice($maxBackups)->each(function ($file) {
                try {
                    File::delete($file->getPathname());
                    Log::info('Old backup deleted', [
                        'file' => $file->getFilename(),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to delete old backup', [
                        'file' => $file->getFilename(),
                        'error' => $e->getMessage(),
                    ]);
                }
            });
        }
    }
}
