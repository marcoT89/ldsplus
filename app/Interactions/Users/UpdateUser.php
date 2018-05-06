<?php

namespace App\Interactions\Users;

use ZachFlower\EloquentInteractions\Interaction;
use App\Models\User;

class UpdateUser extends Interaction
{
    public $validations = [
        'id' => 'required|exists:users,id',
        'name' => 'required|min:3',
        'gender' => 'required|in:male,female',
    ];

    public function execute()
    {
        $user = User::findOrFail($this->id);
        $user->update($this->params);
        return $user->fresh();
    }
}
