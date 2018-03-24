<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    public function organizations()
    {
        return $this->belongsToMany(Organization::class);
    }
}
