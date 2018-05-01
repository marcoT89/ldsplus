<?php

namespace Tests\Unit\Interactions\Users;

use Tests\TestCase;
use Tests\Traits\Interaction;
use App\Interactions\Users\ReleaseCalling;
use App\Models\Calling;
use App\Models\User;

class ReleaseCallingTest extends TestCase
{
    use Interaction;

    protected $interaction = ReleaseCalling::class;

    /** @test */
    public function it_change_calling_status_to_release_with_timestamp()
    {
        // Arrange
        $user = $this->create(User::class);
        $calling = $this->create(Calling::class);
        $user->callings()->attach($calling, [
            'status' => Calling::STATUS_RELEASE,
        ]);

        // Act
        $outcome = $this->interact([
            'user' => $user,
            'calling' => $calling,
        ]);

        // Assert
        $this->assertTrue($outcome->valid);
        $this->assertTrue($outcome->result->releasedCallings->contains($calling));
        $this->assertEquals(now(), $outcome->result->releasedCallings->first()->pivot->released_at);
    }

    /** @test */
    public function it_is_invalid_when_calling_doesnt_have_release_status()
    {
        // Arrange
        $user = $this->create(User::class);
        $calling = $this->create(Calling::class);
        $user->callings()->attach($calling, [
            'status' => Calling::STATUS_INDICATED,
        ]);

        // Act
        $outcome = $this->interact([
            'user' => $user,
            'calling' => $calling,
        ]);

        // Assert
        $this->assertFalse($outcome->valid);
        $this->assertArraySubset([
            'user' => ["User doesn't have this calling to release."]
        ], $outcome->errors->toArray());
    }
}
