<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearningBonusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'selected_courses' => SelectedBonusCourseResource::collection($this->resource['selected_courses'] ?? collect()),
            'available_courses' => BonusCourseResource::collection($this->resource['available_courses'] ?? collect([])),
        ];
    }
}
