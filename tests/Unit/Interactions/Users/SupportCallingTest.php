<?php

namespace Tests\Unit\Interactions\Users;

use Tests\TestCase;
use Tests\Traits\Interaction;
use App\Interactions\Users\SupportCalling;
use App\Models\User;
use App\Models\Calling;

class SupportCallingTest extends TestCase
{
    use Interaction;

    protected $interaction = SupportCalling::class;

    /** @test */
    public function it_changes_status_to_supported_with_timestamp()
    {
        // Arrange
        $user = $this->create(User::class);
        $calling = $this->create(Calling::class);
        $user->callings()->attach($calling, [
            'status' => Calling::STATUS_INDICATED,
        ]);
        $now = now();

        // Act
        $outcome = $this->interact([
            'user' => $user,
            'calling' => $calling,
        ]);

        // Asserts
        $this->assertTrue($outcome->valid);
        $this->assertTrue($outcome->result->supportedCallings->contains($calling));
        $this->assertNotNull($outcome->result->supportedCallings->first()->pivot->supported_at);
    }

    /** @test */
    public function it_is_invalid_if_calling_is_not_indicated_to_the_user()
    {
        // Arrange
        $user = $this->create(User::class);
        $calling = $this->create(Calling::class);

        // Act
        $outcome = $this->interact([
            'user' => $user,
            'calling' => $calling,
        ]);

        // Asserts
        $this->assertFalse($outcome->valid);
        $this->assertArraySubset(
            ['user' => ["User doesn't have this calling indicated."]],
            $outcome->errors->toArray()
        );
    }
}
