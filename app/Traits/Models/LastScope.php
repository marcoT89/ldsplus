<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait LastScope
{
    public function scopeLast(Builder $query, $column = 'created_at')
    {
        return $query->whereNotNull($column)->orderBy($column, 'desc')->first();
    }
}
