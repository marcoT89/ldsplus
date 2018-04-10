<?php

namespace App\Http\Controllers\Api;

use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;

class OrganizationsController extends Controller
{
    public function index(Request $request)
    {
        $request->request->add(['current_ward' => $this->currentWard()]);
        return OrganizationResource::collection(Organization::all());
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Organization $organization)
    {
        //
    }

    public function update(Request $request, Organization $organization)
    {
        //
    }

    public function destroy(Organization $organization)
    {
        //
    }
}
