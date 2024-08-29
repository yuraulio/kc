<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearningBonusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'selected_course' => $this->resource['selected_course'] ?? null,
            'available_courses' => BonusCourseResource::collection($this->resource['available_courses'] ?? collect([])),
            'exams_required' => $this->resource['exams_required'] ?? null,
        ];
    }
}
