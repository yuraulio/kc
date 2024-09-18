<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagsSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'selected_tags' => $this->resource['selected_tags'] ?? [],
            'tags_list' => TagResource::collection($this->resource['tags_list'] ?? collect()),
        ];
    }
}
