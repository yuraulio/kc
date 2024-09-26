<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'has_exam' => $this->resource['has_exam'] ?? false,
            'selected_exam' => $this->resource['selected_exam'] ?? null,
            'exam_accessibility_type' => $this->resource['exam_accessibility_type'] ?? null,
            'exam_accessibility_value' => $this->resource['exam_accessibility_value'] ?? null,
            'exam_repeat_delay' => $this->resource['exam_repeat_delay'] ?? null,
            'whole_amount_should_be_paid' => (bool) ($this->resource['whole_amount_should_be_paid'] ?? false),
            'exams' => ExamResource::collection($this->resource['exams'] ?? collect([])),
        ];
    }
}
