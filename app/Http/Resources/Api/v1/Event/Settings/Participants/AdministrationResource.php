<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdministrationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'admin_title' => $this->resource['admin_title'] ?? null,
            'created_on' => $this->resource['created_on'] ?? null,
        ];
    }
}
