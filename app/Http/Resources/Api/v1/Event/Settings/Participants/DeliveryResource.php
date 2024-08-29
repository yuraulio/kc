<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'course_delivery' => $this->resource['course_delivery'],
            'course_city' => $this->resource['course_city'] ?? null,
            'cities_list' => DeliveryCitiesResource::collection($this->resource['cities_list'] ?? collect([])),
        ];
    }
}
