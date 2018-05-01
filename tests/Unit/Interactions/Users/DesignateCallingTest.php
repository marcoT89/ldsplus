<?php

namespace Tests\Unit\Interactions\Users;

use Tests\TestCase;
use Tests\Traits\Interaction;
use App\Interactions\Users\DesignateCalling;
use App\Models\User;
use App\Models\Calling;

class DesignateCallingTest extends TestCase
{
    use Interaction;

    protected $interaction = DesignateCalling::class;

    /** @test */
    public function it_change_status_to_designated_with_timestamp()
    {
        // Arrange
        $user = $this->create(User::class);
        $calling = $this->create(Calling::class);
        $now = now();
        $user->callings()->attach($calling, [
            'status' => Calling::STATUS_SUPPORTED,
            'supported_at' => $now,
        ]);

        // Act
        $outcome = $this->interact([
            'user' => $user,
            'calling' => $calling
        ]);

        // Assert
        $this->assertTrue($outcome->valid);
        $this->assertTrue($outcome->result->designatedCallings->contains($calling));
        $this->assertEquals($now, $outcome->result->designatedCallings->first()->pivot->designated_at);
    }

    /** @test */
    public function it_is_invalid_if_calling_was_not_supported()
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
            'calling' => $calling
        ]);

        // Assert
        $this->assertFalse($outcome->valid);
        $this->assertArraySubset([
            'user' => ["User doesn't have this calling supported yet."]
        ], $outcome->errors->toArray());
    }
}
