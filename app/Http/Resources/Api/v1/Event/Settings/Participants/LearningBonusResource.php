<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearningBonusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'offer_bonus_course' => count($this->resource['selected_courses'] ?? []) > 0,
            'selected_courses' => $this->resource['selected_courses'] ?? [],
            'exams_required' => (bool) ($this->resource['exams_required'] ?? null),
            'access_period' => $this->resource['access_period'] ?? null,
            'available_courses' => BonusCourseResource::collection($this->resource['available_courses'] ?? collect([])),
        ];
    }
}
