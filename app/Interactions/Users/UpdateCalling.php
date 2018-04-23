<?php

namespace App\Interactions\Users;

use Illuminate\Support\Facades\DB;
use App\Interactions\Interaction;
use App\Rules\SameGender;

class UpdateCalling extends Interaction
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
        return DB::transaction(function () {
            if ($this->calling === null) {
                $this->user->releaseCallings();
                return $this->user->fresh();
            }

            if ($this->user->hasCalling($this->calling)) {
                if ($this->user->willRelease($this->calling)) {
                    $this->user->callingsToAssign()->detach();
                    $this->user->reassignCalling($this->calling);
                }
                return $this->user->fresh();
            }

            if (!$this->user->hasCalling($this->calling)) {
                $this->user->releaseCallings();
                $this->user->callingsToAssign()->detach();
                $this->user->assignCalling($this->calling);
            }

            return $this->user->fresh();
        }, 2);
    }
}
