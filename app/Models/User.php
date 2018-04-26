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
                Calling::STATUS_INDICATED,
                Calling::STATUS_SUPPORTED,
                Calling::STATUS_DESIGNATED,
            ]);
        });
    }

    public function callings()
    {
        return $this->belongsToMany(Calling::class)
            ->withPivot('status', 'designated_at', 'released_at')
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

    public function designatedCallings()
    {
        return $this->callings()->wherePivot('status', Calling::STATUS_DESIGNATED);
    }

    public function releasedCallings()
    {
        return $this->callings()->wherePivot('status', Calling::STATUS_RELEASED);
    }

    public function indicatedCallings()
    {
        return $this->callings()->wherePivot('status', Calling::STATUS_INDICATED);
    }

    public function callingsToRelease()
    {
        return $this->callings()->wherePivot('status', Calling::STATUS_RELEASE);
    }

    public function hasCalling(Calling $calling)
    {
        return $this->hasDesignatedCalling($calling) || $this->willReleaseCalling($calling);
    }

    public function hasDesignatedCalling(Calling $calling)
    {
        return $this->designatedCallings->contains($calling);
    }

    public function indicateCalling(Calling $calling)
    {
        if (!$this->hasCalling($calling)) {
            $this->indicatedCallings()->attach($calling->id, ['status' => Calling::STATUS_INDICATED]);
        }
    }

    public function willReleaseCalling(Calling $calling)
    {
        return $this->callingsToRelease->contains($calling);
    }

    public function redesignateCalling(Calling $calling)
    {
        if ($this->willReleaseCalling($calling)) {
            $this->callingsToRelease()->updateExistingPivot($calling->id, ['status' => Calling::STATUS_DESIGNATED]);
        }
    }

    public function releaseCallings()
    {
        $this->designatedCallings->map->id->each(function ($callingId) {
            $this->designatedCallings()->updateExistingPivot($callingId, [
                'status' => Calling::STATUS_RELEASE,
            ]);
        });
        $this->indicatedCallings()->detach();
    }
}
