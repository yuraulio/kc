<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilesSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'attached_files' => $this->resource['attached_files'] ?? null,
            'available_files' => FilesResource::collection($this->resource['available_files'] ?? collect([])),
        ];
    }
}
