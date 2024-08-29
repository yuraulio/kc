<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CareerPathAndSkillsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'selected_paths' => $this->resource['selected_paths'] ?? null,
            'career_paths' => CareerPathResource::collection($this->resource['career_paths'] ?? collect()),
            'selected_skills' => $this->resource['selected_skills'] ?? null,
            'skills' => DevelopmentSkillResource::collection($this->resource['skills'] ?? collect()),
        ];
    }
}
