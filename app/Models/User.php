<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Traits\BelongsToWard;
use App\Traits\Models\LastScope;
use App\Traits\Models\FilterScope;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, BelongsToWard, LastScope, FilterScope;

    protected $fillable = [
        'name', 'email', 'gender', 'birthday', 'password', 'ward_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeWithoutCalling(Builder $query)
    {
        return $query->whereDoesntHave('callings', function ($query) {
            return $query->whereIn('calling_user.status', [
                Calling::STATUS_ASSIGN,
                Calling::STATUS_ASSIGNED,
            ]);
        });
    }

    public function callings()
    {
        return $this->belongsToMany(Calling::class)
            ->withPivot('status', 'assigned_at', 'released_at')
            ->withTimestamps();
    }

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function notReleasedCallings()
    {
        return $this->callings()->wherePivot('status', '!=', Calling::STATUS_RELEASED);
    }

    public function assignedCallings()
    {
        return $this->callings()->wherePivot('status', Calling::STATUS_ASSIGNED);
    }

    public function releasedCallings()
    {
        return $this->callings()->wherePivot('status', Calling::STATUS_RELEASED);
    }

    public function callingsToAssign()
    {
        return $this->callings()->wherePivot('status', Calling::STATUS_ASSIGN);
    }

    public function callingsToRelease()
    {
        return $this->callings()->wherePivot('status', Calling::STATUS_RELEASE);
    }

    public function hasCalling(Calling $calling)
    {
        return $this->assignedCallings->contains($calling) || $this->callingsToRelease->contains($calling);
    }

    public function assignCalling(Calling $calling)
    {
        if (!$this->hasCalling($calling)) {
            $this->assignedCallings()->attach($calling->id, ['status' => Calling::STATUS_ASSIGN]);
        }
    }

    public function willRelease(Calling $calling)
    {
        return $this->callingsToRelease->contains($calling);
    }

    public function reassignCalling(Calling $calling)
    {
        if ($this->willRelease($calling)) {
            $this->callingsToRelease()->updateExistingPivot($calling->id, ['status' => Calling::STATUS_ASSIGNED]);
        }
    }

    public function releaseCallings()
    {
        $this->assignedCallings->map->id->each(function ($callingId) {
            $this->assignedCallings()->updateExistingPivot($callingId, [
                'status' => Calling::STATUS_RELEASE,
            ]);
        });
        $this->callingsToAssign()->detach();
    }
}
