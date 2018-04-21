<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Interactions\User\UpdateCalling;
use App\Models\Calling;
use Illuminate\Http\Response;
use App\Http\Requests\UserRequest;
use App\Interactions\Users\CreateUser;
use App\Filters\UserFilters;

class UsersController extends Controller
{
    public function index()
    {
        return UserResource::collection($this->currentWardUsers()->get());
    }

    public function withoutCalling(UserFilters $filters)
    {
        return UserResource::collection($this->currentWardUsers()->withoutCalling()->filter($filters)->get());
    }

    public function checkStatus(Request $request)
    {
        $user = User::where('id', $request->user_id)->with('callings')->first();
        $calling = Calling::where('id', $request->calling_id)->with('organization')->first();
        $outcome = UpdateCalling::run([
            'user' => $user,
            'calling' => $calling,
        ]);

        if (!$outcome->valid) {
            return response()->json(['errors' => $outcome->errors->toArray()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new UserResource($outcome->result);
    }

    public function store(UserRequest $request)
    {
        $outcome = CreateUser::run(array_merge($request->validated(), ['ward_id' => $this->currentWard()->id]));

        if (!$outcome->valid) {
            return response()->json(['errors' => $outcome->errors->toArray()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new UserResource($outcome->result);
    }

    public function show(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }
}
