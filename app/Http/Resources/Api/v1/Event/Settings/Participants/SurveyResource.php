<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'course_satisfaction_url' => $this->resource['course_satisfaction_url'] ?? null,
            'instructors_url' => $this->resource['instructors_url'] ?? null,
            'send_after_days' => $this->resource['send_after_days'] ?? null,
        ];
    }
}
