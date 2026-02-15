<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotspotVoucher extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'validity_days', 'max_uses', 'profile_id'];

    public function profile()
    {
        return $this->belongsTo(HotspotProfile::class, 'profile_id');
    }
}
