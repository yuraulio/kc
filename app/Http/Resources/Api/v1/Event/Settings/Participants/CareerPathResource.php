<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CareerPathResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
        ];
    }
}
