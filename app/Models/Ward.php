<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $fillable = ['name'];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class);
    }
}
