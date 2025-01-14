<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\v1\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPaymentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $statusHistory = $this->transaction->first()?->status_history;

        $transactionsCount = $this->transaction->count();

        $instalments = isset($statusHistory[0]) ? (int)$statusHistory[0]['installments'] ?? 'Full' : 'Full';

        $haveInstalments = is_int($instalments) && $transactionsCount != $instalments;

        return [
            'id'         => $this->id,
            'created_at' => $this->created_at,
            'event'      => $this->event,
            'amount'     => round((int)$this->amount, 2),
            'status'     => $haveInstalments ? 2 : (int)$this->pivot->paid,
            'balance'    => $haveInstalments
                ? round(abs((($this->amount / $instalments) * $transactionsCount) - $this->amount), 2)
                : 0,
        ];
    }
}
