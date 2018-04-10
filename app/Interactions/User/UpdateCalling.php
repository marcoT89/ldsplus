<?php

namespace App\Interactions\User;

use ZachFlower\EloquentInteractions\Interaction;
use App\Models\Calling;
use Illuminate\Support\Facades\DB;

class UpdateCalling extends Interaction
{
    public $validations = [
        'user' => 'required|object:App\Models\User',
        'calling' => 'nullable|object:App\Models\Calling',
    ];

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
