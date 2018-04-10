<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))->merge([
            'callings' => OrganizationCallingResource::collection($this->callings),
        ])->toArray();
    }
}
