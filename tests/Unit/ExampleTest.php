<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class ExampleTest extends TestCase
{
    public function testBasicTest()
    {
        $this->assertTrue(true);
        $user = $this->prophesize(User::class);
        $user->ward()->shouldBeCalled();
        $user->reveal()->ward();
    }
}
