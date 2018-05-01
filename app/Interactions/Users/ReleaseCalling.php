<?php

namespace App\Interactions\Users;

use App\Interactions\Interaction;
use App\Rules\SameGender;
use App\Models\Calling;

class ReleaseCalling extends Interaction
{
    public function validations()
    {
        return [
            'calling' => 'nullable|object:App\Models\Calling',
            'user' => ['required', 'object:App\Models\User', new SameGender(optional($this->calling)->gender)],
        ];
    }

    public function execute()
    {
        if (!$this->user->callingsToRelease->contains($this->calling)) {
            $this->validator->errors()->add('user', "User doesn't have this calling to release.");
            return;
        }

        $this->user->callingsToRelease()->updateExistingPivot($this->calling, [
            'status' => Calling::STATUS_RELEASED,
            'released_at' => now(),
        ]);

        return $this->user->fresh();
    }
}
