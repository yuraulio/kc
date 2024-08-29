<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'access_duration' => $this->resource['access_duration'] ?? null,
            'files_access_till' => $this->resource['files_access_till'] ?? null,
        ];
    }
}
