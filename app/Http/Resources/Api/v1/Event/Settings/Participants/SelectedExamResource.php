<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SelectedExamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'exam_accessibility_type' => $this->resource->pivot?->exam_accessibility_type,
            'exam_accessibility_value' => $this->resource->pivot?->exam_accessibility_value,
            'exam_repeat_delay' => $this->resource->pivot?->exam_repeat_delay,
        ];
    }
}
