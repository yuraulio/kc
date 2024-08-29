<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SelectedPaymentsOptionsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'option_id' => $this->resource->id,
            'active' => (bool) $this->resource->pivot?->active,
            'installments_allowed' => (bool) $this->resource->pivot?->installments_allowed,
            'monthly_installments_limit' => $this->resource->pivot?->monthly_installments_limit,
        ];
    }
}
