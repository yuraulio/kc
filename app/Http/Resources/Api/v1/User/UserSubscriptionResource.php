<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\v1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'price'             => $this->price,
            'registration_date' => $this->transactions->first()?->created_at,
            'expiration_date'   => $this->pivot->expiration ?? null,
            'payment'           => $this->transactions->first()?->type === 'Sponsored' ? 'Sponsored' : 'Paid',
            'progress'          => $this->progress,
        ];
    }
}
