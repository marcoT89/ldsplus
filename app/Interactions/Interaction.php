<?php

namespace App\Interactions;

use ZachFlower\EloquentInteractions\Interaction as BaseInteraction;

abstract class Interaction extends BaseInteraction
{
    protected function hasErrors() : bool
    {
        return $this->validator->errors()->any();
    }
}
