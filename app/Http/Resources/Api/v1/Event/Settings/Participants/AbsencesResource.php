<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsencesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'limit' => $this->resource['limit'] ?? null,
            'starting_hours' => $this->resource['starting_hours'] ?? null,
        ];
    }
}
