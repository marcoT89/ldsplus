<?php

namespace App\Interactions\Users;

use App\Interactions\Interaction;
use App\Models\Calling;
use App\Rules\SameGender;

class SupportCalling extends Interaction
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
        if (!$this->user->indicatedCallings->contains($this->calling)) {
            $this->validator->errors()->add('user', "User doesn't have this calling indicated.");
            return;
        }

        $this->user->indicatedCallings()->updateExistingPivot($this->calling, [
            'status' => Calling::STATUS_SUPPORTED,
            'supported_at' => now(),
        ]);

        return $this->user->fresh();
    }
}
