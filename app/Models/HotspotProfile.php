<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotspotProfile extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'rate_limit', 'session_timeout'];

    public function users()
    {
        return $this->hasMany(HotspotUser::class, 'profile_id');
    }

    public function vouchers()
    {
        return $this->hasMany(HotspotVoucher::class, 'profile_id');
    }
}
