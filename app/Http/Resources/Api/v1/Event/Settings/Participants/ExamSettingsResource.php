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
            'selected_exam' => isset($this->resource['selected_exam']) ? SelectedExamResource::make($this->resource['selected_exam']) : null,
            'exams' => ExamResource::collection($this->resource['exams'] ?? collect([])),
        ];
    }
}
