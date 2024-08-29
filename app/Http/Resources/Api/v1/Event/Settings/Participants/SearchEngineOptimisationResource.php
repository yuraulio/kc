<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchEngineOptimisationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->resource['slug'] ?? null,
            'meta_title' => $this->resource['meta_title'] ?? null,
            'meta_description' => $this->resource['meta_description'] ?? null,
        ];
    }
}
