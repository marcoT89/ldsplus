<?php

namespace App\Models;

class Ward extends BaseModel
{
    protected $fillable = ['name'];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class);
    }
}
