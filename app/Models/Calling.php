<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Calling extends BaseModel
{
    const STATUS_INDICATED = 'indicated';
    const STATUS_SUPPORTED = 'supported';
    const STATUS_DESIGNATED = 'designated';
    const STATUS_RELEASED = 'released';
    const STATUS_RELEASE = 'release';
    const STATUS_INVALID = 'invalid';

    public function scopeActive($query)
    {
        return $query->whereHas('users', function ($query) {
            return $query->where('calling_user.status', '!=', self::STATUS_RELEASED);
        });
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('status', 'designated_at', 'released_at')
            ->withTimestamps();
    }

    public function scopeChanges(Builder $query)
    {
        return $query->whereHas('users', function ($userQuery) {
            return $userQuery->whereHas('indicatedCallings')
                ->orWhereHas('callingsToRelease')
                ->orWhereHas('supportedCallings');
        });
    }

    public function scopeOfWard(Builder $query, Ward $ward)
    {
        return $query->whereHas('users', function ($userQuery) use ($ward) {
            return $userQuery->whereWardId($ward->id);
        });
    }
}
