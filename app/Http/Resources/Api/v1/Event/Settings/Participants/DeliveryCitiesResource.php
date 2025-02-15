<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryCitiesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id ?? null,
            'name' => $this->resource->name ?? null,
            'country' => $this->resource->country->name ?? null,
        ];
    }
}
