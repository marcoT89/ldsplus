<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Calling extends Model
{
    const STATUS_ASSIGN = 'assign';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_RELEASE = 'release';
    const STATUS_RELEASED = 'released';
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
            ->withPivot('status', 'assigned_at', 'released_at')
            ->withTimestamps();
    }

    public function scopeChanges(Builder $query)
    {
        return $query->whereHas('users', function ($userQuery) {
            return $userQuery->whereHas('callingsToAssign')
                ->orWhereHas('callingsToRelease');
        });
    }
}
