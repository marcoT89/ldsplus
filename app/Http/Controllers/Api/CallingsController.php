<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationUserResource;
use App\Models\User;

class CallingsController extends Controller
{
    public function changes()
    {
        return OrganizationUserResource::collection(
            User::with('callings')->whereHas('indicatedCallings')
                ->orWhereHas('callingsToRelease')
                ->paginate()
        );
    }
}
