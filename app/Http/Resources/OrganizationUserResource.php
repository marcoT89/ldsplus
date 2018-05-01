<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationUserResource extends JsonResource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))->merge([
            'changes' => $this->callings,
        ])->toArray();
    }
}
