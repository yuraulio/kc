<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AudienceSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'selected_audiences' => $this->resource['selected_audiences'] ?? [],
            'audiences_list' => AudienceResource::collection($this->resource['audiences_list'] ?? collect()),
        ];
    }
}
