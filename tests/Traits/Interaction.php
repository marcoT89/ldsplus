<?php

namespace Tests\Traits;

trait Interaction
{
    public function interact($params = [])
    {
        return $this->interaction::run($params);
    }
}
