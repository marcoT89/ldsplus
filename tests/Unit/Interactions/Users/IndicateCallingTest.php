<?php

namespace Tests\Unit\Interactions\Users;

use Tests\TestCase;
use App\Models\User;
use App\Models\Calling;
use Carbon\Carbon;
use Tests\Traits\Interaction;
use App\Interactions\Users\IndicateCalling;

class IndicateCallingTest extends TestCase
{
    use Interaction;

    private $user;
    private $interaction = IndicateCalling::class;

    protected function setUp()
    {
        parent::setUp();
        $this->user = factory(\App\Models\User::class)->create();
    }

    private function subject(User $user, ? Calling $calling)
    {
        return $this->interact([
            'user' => $user,
            'calling' => $calling,
        ]);
    }

    /** @test */
    public function it_has_one_indicated_calling_when_receive_one()
    {
        $calling = $this->create(Calling::class);

        $outcome = $this->subject($this->user, $calling);
        $this->user = $outcome->result;

        $this->assertTrue($outcome->valid);
        $this->assertInternalType('int', $this->user->id);
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertCount(1, $this->user->indicatedCallings);
        $this->assertEmpty($this->user->designatedCallings);
    }

    /** @test */
    public function it_has_one_indicated_calling_when_has_released_callings_and_receives_another_one()
    {
        $callings = factory(Calling::class, 3)->create()->each(function ($calling) {
            $calling->users()->save($this->user);
            $calling->users()->updateExistingPivot($this->user->id, [
                'status' => Calling::STATUS_RELEASED,
                'released_at' => Carbon::now(),
            ]);
        });
        $calling = $this->create(Calling::class);

        $outcome = $this->subject($this->user, $calling);
        $this->user = $outcome->result;

        $this->assertTrue($outcome->valid);
        $this->assertCount(3, $this->user->releasedCallings);
        $this->assertCount(1, $this->user->indicatedCallings);
    }

    /** @test */
    public function it_puts_designated_callings_to_release_when_receive_a_new_one()
    {
        // Arrange
        $designated = $this->create(Calling::class);
        $released = $this->create(Calling::class);
        $newCalling = $this->create(Calling::class);

        $released->users()->save($this->user);
        $released->users()->updateExistingPivot($this->user, [
            'status' => Calling::STATUS_RELEASED,
            'released_at' => Carbon::now(),
        ]);

        $designated->users()->save($this->user);
        $designated->users()->updateExistingPivot($this->user, [
            'status' => Calling::STATUS_DESIGNATED,
            'designated_at' => Carbon::now(),
        ]);

        // Asserts
        $this->assertCount(1, $this->user->designatedCallings);
        $this->assertCount(1, $this->user->releasedCallings);

        // Act
        $outcome = $this->subject($this->user, $newCalling);
        $this->user = $outcome->result;

        // Asserts
        $this->assertTrue($outcome->valid);
        $this->assertTrue($this->user->indicatedCallings->contains($newCalling));
        $this->assertTrue($this->user->callingsToRelease->contains($designated));
        $this->assertTrue($this->user->releasedCallings->contains($released));
        $this->assertCount(1, $this->user->releasedCallings);
    }

    /** @test */
    public function it_removes_callings_to_assign_when_receives_another_one()
    {
        $callingToAssign = $this->create(Calling::class);
        $this->user->callings()->save($callingToAssign);
        $this->user->callings()->updateExistingPivot($callingToAssign->id, [
            'status' => Calling::STATUS_INDICATED,
        ]);
        $calling = $this->create(Calling::class);

        $outcome = $this->subject($this->user, $calling);
        $this->user = $outcome->result;

        $this->assertTrue($outcome->valid);
        $this->assertCount(1, $this->user->callings);
    }

    /** @test */
    public function it_redesignates_calling_when_receives_the_same_to_be_released()
    {
        // Arrange
        $designated = $this->create(Calling::class);
        $this->user->callings()->save($designated);
        $this->user->callings()->updateExistingPivot($designated->id, [
            'status' => Calling::STATUS_DESIGNATED,
            'designated_at' => Carbon::now(),
        ]);
        $this->user->releaseCallings();
        $this->user->refresh();

        // Asserts
        $this->assertCount(1, $this->user->callingsToRelease);
        $this->assertCount(0, $this->user->designatedCallings);

        // Act
        $outcome = $this->subject($this->user, $designated);
        $this->user = $outcome->result;

        // Asserts
        $this->assertTrue($outcome->valid);
        $this->assertCount(1, $this->user->designatedCallings);
        $this->assertCount(0, $this->user->callingsToRelease);
    }

    /** @test */
    public function it_releases_from_all_callings_when_calling_is_null()
    {
        // Arrange
        $designated = $this->create(Calling::class);
        $this->user->callings()->save($designated);
        $this->user->callings()->updateExistingPivot($designated->id, [
            'status' => Calling::STATUS_DESIGNATED,
            'designated_at' => Carbon::now(),
        ]);
        $this->user->refresh();

        // Asserts
        $this->assertTrue($this->user->designatedCallings->contains($designated));

        // Acts
        $outcome = $this->subject($this->user, null);
        $this->user = $outcome->result;

        // Asserts
        $this->assertTrue($outcome->valid);
        $this->assertFalse($this->user->designatedCallings->contains($designated));
        $this->assertTrue($this->user->callingsToRelease->contains($designated));
    }

    /** @test */
    public function it_is_invalid_when_calling_gender_is_not_allowed()
    {
        $gender = $this->user->gender == 'male' ? 'female' : 'male';
        $maleCalling = factory(Calling::class)->create(['gender' => $gender]);

        $outcome = $this->subject($this->user, $maleCalling);

        $this->assertFalse($outcome->valid);
    }
}
