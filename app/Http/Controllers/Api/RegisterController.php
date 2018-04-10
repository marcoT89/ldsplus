<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    public function store(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        Auth::login($user);

        return new UserResource($user);
    }
}
