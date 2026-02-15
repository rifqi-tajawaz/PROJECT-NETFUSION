<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrialClaim extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'browser_fingerprint',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
