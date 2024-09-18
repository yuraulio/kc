<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentsSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'is_free' => $this->resource['is_free'] ?? null,
            'selected_gateway' => $this->resource['selected_gateway'] ?? null,
            'gateways' => PaymentsGatewaysResource::collection($this->resource['gateways'] ?? collect([])),
            'selected_payment_options' => SelectedPaymentsOptionsResource::collection($this->resource['selected_payment_options'] ?? collect()),
            'options' => PaymentsOptionsResource::collection($this->resource['options'] ?? collect([])),
        ];
    }
}
