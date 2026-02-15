<?php

namespace App\Events\System;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BackupCompleted
{
    use Dispatchable, SerializesModels;

    public string $backupPath;
    public int $size;
    public string $type;
    public $completedAt;

    /**
     * Create a new event instance.
     */
    public function __construct(string $backupPath, int $size, string $type = 'full')
    {
        $this->backupPath = $backupPath;
        $this->size = $size;
        $this->type = $type;
        $this->completedAt = now();
    }
}
