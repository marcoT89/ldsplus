<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $requeset;
    protected $filters = [];
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->filters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    private function filters()
    {
        return array_filter($this->request->only($this->filters));
    }
}
