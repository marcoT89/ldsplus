<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationUserResource extends JsonResource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))->merge([
            'callings' => CallingResource::collection($this->callings),
        ])->toArray();
    }
}
