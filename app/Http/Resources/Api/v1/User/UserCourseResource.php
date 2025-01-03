<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\v1\User;

use App\Model\User;
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

        $transactionsCount = $this->transactions->count();

        $instalments = isset($statusHistory[0]) ? (int)$statusHistory[0]['installments'] ?? 'Full' : 'Full';

        $user = User::find($this->pivot->user_id);

        $examIds = $this->resource->exam()->pluck('exams.id')->toArray();

        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'registration_date' => $this->transactions->first()?->created_at,
            'expiration_date'   => Carbon::parse($this->pivot->expiration) ?? null,
            'delivery'          => $this->delivery,
            'ticket'            => $this->tickets,
            'price'             => $this->transactions->first()?->amount,
            'installments'      => $instalments,
            'access_status'     => Carbon::now()->gt(Carbon::parse($this->pivot->expiration)),
            'payment_status'    => is_int($instalments) && $transactionsCount != $instalments ? 2 : (int)$this->pivot->paid,
            'progress'          => $this->progress,
            'exam_results'      => $user->examResults()
                ->with('examSettings')
                ->whereIn('exam_results.exam_id', $examIds)
                ->get(),
        ];
    }
}
