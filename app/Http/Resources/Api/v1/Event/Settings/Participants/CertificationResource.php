<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'has_certification' => $this->resource['has_certification'] ?? null,
            'completion_title' => $this->resource['completion_title'] ?? null,
            'diploma_title' => $this->resource['diploma_title'] ?? null,
        ];
    }
}
