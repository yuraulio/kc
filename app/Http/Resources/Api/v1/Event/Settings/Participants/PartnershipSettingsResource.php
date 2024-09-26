<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnershipSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'course_in_collaboration' => isset($this->resource['selected_partner']) && $this->resource['selected_partner'],
            'selected_partner' => $this->resource['selected_partner'] ?? null,
            'available_partners' => PartnershipResource::collection($this->resource['available_partners'] ?? collect([])),
        ];
    }
}
