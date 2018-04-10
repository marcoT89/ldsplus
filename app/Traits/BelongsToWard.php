<?php

namespace App\Traits;

use App\Models\Ward;

trait BelongsToWard
{
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function scopeOfWard($query, $ward)
    {
        if ($ward instanceof Ward) {
            return $query->where('ward_id', $ward->id);
        }

        return $query->where('ward_id', $ward);
    }
}
