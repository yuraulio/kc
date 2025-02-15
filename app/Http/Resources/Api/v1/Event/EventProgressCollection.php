<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\v1\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventProgressCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $decimal = round((float) $this->eventInfo['course_hours'], 1) ?? 0;
        $hours = floor($decimal);
        $minutes = round(($decimal - $hours) * 60);

        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'admin_title'  => $this->admin_title,
            'subtitle'     => $this->subtitle,
            'status'       => $this->status,
            'hours'        => $hours,
            'minutes'      => $minutes,
            'completed_at' => $this->completed_at,
            'launch_date'  => $this->launch_date,
            'delivery'     => $this->delivery()->first(),
            'progress'     => round($this->progress($this->users->first())),
        ];
    }
}
