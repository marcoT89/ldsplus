<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\Filters;

trait FilterScope
{
    public function scopeFilter(Builder $builder, Filters $filters)
    {
        return $filters->apply($builder);
    }
}
