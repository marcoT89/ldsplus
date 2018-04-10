<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    const GENDER_TYPE_MALE = 'male';
    const GENDER_TYPE_FEMALE = 'female';
    const GENDER_TYPE_BOTH = 'both';

    public function wards()
    {
        return $this->belongsToMany(Ward::class);
    }

    public function callings()
    {
        return $this->hasMany(Calling::class);
    }
}
