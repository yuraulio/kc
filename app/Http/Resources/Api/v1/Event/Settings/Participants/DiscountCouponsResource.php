<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountCouponsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'code_coupon' => $this->resource->code_coupon,
            'price' => (float) $this->resource->price,
            'percentage' => $this->resource->percentage,
            'used' => $this->resource->used,
            'status' => $this->resource->status,
        ];
    }
}
