<?php

namespace Tests\Unit;

use App\Rules\SameGender;
use App\Models\User;
use Tests\TestCase;

class SameGenderTest extends TestCase
{
    /** @test */
    public function it_fails_when_gender_is_different_from_user_gender()
    {
        $this->assertFalse($this->rule('male')->passes('user_gender', $this->user('female')));
    }

    /** @test */
    public function it_passes_if_value_is_null()
    {
        $this->assertTrue($this->rule('male')->passes('user_gender', null));
    }

    /** @test */
    public function it_passes_when_is_the_same_gender()
    {
        $this->assertTrue($this->rule('female')->passes('user_gender', $this->user('female')));
    }

    private function rule($gender)
    {
        return new SameGender($gender);
    }

    private function user($gender = 'male')
    {
        return factory(User::class)->make(['gender' => $gender]);
    }
}
