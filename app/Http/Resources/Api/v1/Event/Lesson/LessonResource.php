<?php

namespace App\Http\Resources\Api\v1\Event\Lesson;

use App\Http\Resources\Api\v1\Event\Instructor\InstructorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'instructor' => InstructorResource::collection($this->whenLoaded('instructor')),
            'vimeo_video' => $this->resource->vimeo_video,
            'vimeo_duration' => $this->resource->vimeo_duration,
            'pivot' => [
                'date' => $this->resource->pivot->date,
                'room' => $this->resource->pivot->room,
                'time_ends' => $this->resource->pivot->time_ends,
                'time_starts' => $this->resource->pivot->time_starts,
            ],
        ];
    }
}
