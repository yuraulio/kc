<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\v1\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $statusHistory = $this->transactions->first()?->status_history;

        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'registration_date' => $this->transactions->first()?->created_at,
            'expiration_date'   => $this->pivot->expiration ?? null,
            'delivery'          => $this->delivery,
            'ticket'            => $this->tickets,
            'price'             => $this->transactions->first()?->amount,
            'installments'      => isset($statusHistory[0]) ? ['installments'] ?? 'Full' : 'Full',
            'access_status'     => Carbon::now()->gt(Carbon::parse($this->pivot->expiration)),
            'payment_status'    => (bool) $this->pivot->paid,
            'progress'          => $this->progress,
        ];
    }
}
