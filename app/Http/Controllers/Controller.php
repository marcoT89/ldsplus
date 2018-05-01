<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use Illuminate\Validation\ValidationException;

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

    protected function interact(string $interactor, $params = [])
    {
        $outcome = $interactor::run($params);

        throw_unless($outcome->valid, new ValidationException($outcome->validator));

        return $outcome;
    }
}
