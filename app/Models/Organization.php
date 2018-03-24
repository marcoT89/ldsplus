<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    public function wards()
    {
        return $this->belongsToMany(Ward::class);
    }

    public function callings()
    {
        return $this->hasMany(Calling::class);
    }
}
