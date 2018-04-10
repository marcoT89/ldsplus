<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationCallingResource extends JsonResource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))->merge([
            'users' => [],
        ])->toArray();
    }
}
