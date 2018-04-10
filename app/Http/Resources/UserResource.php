<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))->merge([
            'callings' => $this->notReleasedCallings,
        ])->toArray();
    }
}
