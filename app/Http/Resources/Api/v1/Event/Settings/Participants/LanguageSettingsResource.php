<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'selected_language' => $this->resource['selected_language'] ?? null,
            'languages' => LanguageResource::collection($this->resource['languages'] ?? collect([])),
        ];
    }
}
