<?php

namespace App\Interactions\Users;

use ZachFlower\EloquentInteractions\Interaction;
use App\Models\User;

class CreateUser extends Interaction
{
    public $validations = [
        'name' => 'required|min:3',
        'ward_id' => 'required|exists:wards,id',
        'gender' => 'required|in:male,female',
    ];

    public function execute()
    {
        return User::create($this->params);
    }
}
