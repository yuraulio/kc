<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SelectedBonusCourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'exams_required' => (bool) ($this->resource->pivot?->exams_required ?? null),
            'access_period' => $this->resource->pivot?->access_period ?? null,
        ];
    }
}
