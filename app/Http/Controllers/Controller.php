<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function currentUser()
    {
        return auth()->user();
    }

    public function currentWard()
    {
        return $this->currentUser()->ward;
    }

    public function currentWardUsers()
    {
        return User::ofWard($this->currentWard());
    }
}
