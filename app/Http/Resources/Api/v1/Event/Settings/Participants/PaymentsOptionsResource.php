<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentsOptionsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'active' => false,
            'installments_allowed' => false,
            'monthly_installments_limit' => '',
        ];
    }
}
