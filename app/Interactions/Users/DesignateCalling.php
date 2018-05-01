<?php

namespace App\Interactions\Users;

use App\Interactions\Interaction;
use App\Models\Calling;
use App\Rules\SameGender;

class DesignateCalling extends Interaction
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
        if (!$this->user->supportedCallings->contains($this->calling)) {
            $this->validator->errors()->add('user', "User doesn't have this calling supported yet.");
            return;
        }

        $this->user->supportedCallings()->updateExistingPivot($this->calling, [
            'status' => Calling::STATUS_DESIGNATED,
            'designated_at' => now(),
        ]);

        return $this->user->fresh();
    }
}
