<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'selected_exams' => SelectedExamResource::collection($this->resource['selected_exams'] ?? collect([])),
            'exams' => ExamResource::collection($this->resource['exams'] ?? collect([])),
        ];
    }
}
