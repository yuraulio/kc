<?php

namespace App\Http\Resources\Api\v1\Event\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'kc_id' => $this->resource->kc_id,
            'firstname' => $this->resource->firstname,
            'lastname' => $this->resource->lastname,
            'email' => $this->resource->email,
            'rating' => $this->resource->rating,
            'review_title' => $this->resource->review_title,
            'review_desc' => $this->resource->review_desc,
            'date' => $this->resource->date,
            'published' => $this->resource->published,
            'event_id' => $this->resource->event_id,
            'student_id' => $this->resource->student_id,
            'profileImage' => $this->resource->student_id,
        ];
    }
}
