<?php

namespace Tests\Unit\Interactions\User;

use Tests\TestCase;
use App\Models\User;
use App\Models\Calling;
use App\Interactions\User\UpdateCalling;
use Carbon\Carbon;

class UpdateCallingTest extends TestCase
{
    private $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = factory(\App\Models\User::class)->create();
    }

    private function subject(User $user, ? Calling $calling)
    {
        return UpdateCalling::run([
            'user' => $user,
            'calling' => $calling,
        ]);
    }

    /** @test */
    public function when_has_no_calling_and_receive_one()
    {
        $calling = factory(Calling::class)->create();

        $outcome = $this->subject($this->user, $calling);
        $this->user = $outcome->result->fresh();

        $this->assertTrue($outcome->valid);
        $this->assertInternalType('int', $this->user->id);
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertCount(1, $this->user->callingsToAssign);
        $this->assertEmpty($this->user->assignedCallings);
    }

    /** @test */
    public function when_has_released_callings_and_receives_another_one()
    {
        $callings = factory(Calling::class, 3)->create()->each(function ($calling) {
            $calling->users()->save($this->user);
            $calling->users()->updateExistingPivot($this->user->id, [
                'status' => Calling::STATUS_RELEASED,
                'released_at' => Carbon::now(),
            ]);
        });
        $calling = factory(Calling::class)->create();

        $outcome = $this->subject($this->user, $calling);
        $this->user = $outcome->result->fresh();

        $this->assertTrue($outcome->valid);
        $this->assertCount(3, $this->user->releasedCallings);
        $this->assertCount(1, $this->user->callingsToAssign);
    }

    /** @test */
    public function when_has_assigned_calling_and_receives_a_new_one()
    {
        $assigned = factory(Calling::class)->create();
        $released = factory(Calling::class)->create();
        $calling = factory(Calling::class)->create();
        $released->users()->save($this->user);
        $released->users()->updateExistingPivot($this->user->id, [
            'status' => Calling::STATUS_RELEASED,
            'released_at' => Carbon::now(),
        ]);
        $assigned->users()->save($this->user);
        $assigned->users()->updateExistingPivot($this->user->id, [
            'status' => Calling::STATUS_ASSIGNED,
            'assigned_at' => Carbon::now(),
        ]);

        $this->assertCount(1, $this->user->assignedCallings);
        $this->assertCount(1, $this->user->releasedCallings);

        $outcome = $this->subject($this->user, $calling);
        $this->user = $outcome->result->fresh();

        $this->assertTrue($outcome->valid);
        $this->assertCount(1, $this->user->callingsToAssign);
        $this->assertCount(1, $this->user->callingsToRelease);
        $this->assertCount(1, $this->user->releasedCallings);
    }

    /** @test */
    public function when_has_calling_to_assign_and_receives_another_one()
    {
        $callingToAssign = factory(Calling::class)->create();
        $this->user->callings()->save($callingToAssign);
        $this->user->callings()->updateExistingPivot($callingToAssign->id, [
            'status' => Calling::STATUS_ASSIGN,
        ]);
        $calling = factory(Calling::class)->create();

        $outcome = $this->subject($this->user, $calling);
        $this->user = $outcome->result->fresh();

        $this->assertTrue($outcome->valid);
        $this->assertCount(1, $this->user->callings);
    }

    /** @test */
    public function reasign_calling_when_receives_the_same_to_be_released()
    {
        $callingToRelease = factory(Calling::class)->create();
        $this->user->callings()->save($callingToRelease);
        $this->user->callings()->updateExistingPivot($callingToRelease->id, [
            'status' => Calling::STATUS_RELEASE,
            'assigned_at' => Carbon::now(),
        ]);

        $this->assertCount(1, $this->user->callingsToRelease);
        $this->assertCount(0, $this->user->assignedCallings);

        $outcome = $this->subject($this->user, $callingToRelease);
        $this->user = $outcome->result->fresh();

        $this->assertTrue($outcome->valid);
        $this->assertCount(1, $this->user->assignedCallings);
        $this->assertCount(0, $this->user->callingsToRelease);
    }

    /** @test */
    public function when_calling_is_null_it_releases_from_all_callings()
    {
        $assigned = factory(Calling::class)->create();
        $this->user->callings()->save($assigned);
        $this->user->callings()->updateExistingPivot($assigned->id, [
            'status' => Calling::STATUS_ASSIGNED,
            'assigned_at' => Carbon::now(),
        ]);

        $this->assertCount(1, $this->user->assignedCallings);

        $outcome = $this->subject($this->user, null);
        $this->user = $outcome->result->fresh();

        $this->assertTrue($outcome->valid);
        $this->assertCount(0, $this->user->assignedCallings);
        $this->assertCount(1, $this->user->callingsToRelease);
    }
}
