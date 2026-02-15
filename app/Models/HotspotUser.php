<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotspotUser extends Model
{
    use HasFactory;

    protected $fillable = ['username', 'password', 'email', 'profile_id'];

    public function profile()
    {
        return $this->belongsTo(HotspotProfile::class, 'profile_id');
    }
}
