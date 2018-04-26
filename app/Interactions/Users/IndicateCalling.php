<?php

namespace App\Interactions\Users;

use Illuminate\Support\Facades\DB;
use App\Interactions\Interaction;
use App\Rules\SameGender;

class IndicateCalling extends Interaction
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

            if ($this->user->hasDesignatedCalling($this->calling)) {
                return $this->user->fresh();
            }

            if ($this->user->willReleaseCalling($this->calling)) {
                $this->user->indicatedCallings()->detach();
                $this->user->redesignateCalling($this->calling);
                return $this->user->fresh();
            }

            $this->user->releaseCallings();
            $this->user->indicatedCallings()->detach();
            $this->user->indicateCalling($this->calling);

            return $this->user->fresh();
        }, 2);
    }
}
