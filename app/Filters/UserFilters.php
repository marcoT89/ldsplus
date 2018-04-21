<?php

namespace App\Filters;

class UserFilters extends Filters
{
    protected $filters = ['byName'];

    public function byName($name)
    {
        return $this->builder->where('name', 'ilike', "%{$name}%");
    }
}
