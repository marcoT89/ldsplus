<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calling extends Model
{
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
