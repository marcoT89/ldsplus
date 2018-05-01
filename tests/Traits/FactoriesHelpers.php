<?php

namespace Tests\Traits;

trait FactoriesHelpers
{
    protected function create($model, $attributes = [], $quantity = null)
    {
        if ($quantity) {
            return factory($model, $quantity)->create($attributes);
        }
        return factory($model)->create($attributes);
    }

    protected function make($model, $attributes = [], $quantity = null)
    {
        if ($quantity) {
            return factory($model, $quantity)->make($attributes);
        }
        return factory($model)->make($attributes);
    }
}
