<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RelatedCoursesSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'related_ids' => $this->resource['related_ids'] ?? [],
            'active_courses' => EventSimpleResource::collection($this->resource['active_courses'] ?? collect()),
        ];
    }
}
