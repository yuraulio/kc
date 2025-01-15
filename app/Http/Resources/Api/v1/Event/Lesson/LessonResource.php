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
            'id'                     => $this->resource->id,
            'htmlTitle'              => $this->resource->htmlTitle,
            'title'                  => $this->resource->title,
            'subtitle'               => $this->resource->subtitle,
            'header'                 => $this->resource->header,
            'summary'                => $this->resource->summary,
            'status'                 => (bool)$this->resource->status,
            'body'                   => $this->resource->body,
            'instructor'             => InstructorResource::collection($this->whenLoaded('instructor')),
            'vimeo_video'            => $this->resource->vimeo_video,
            'vimeo_duration'         => $this->resource->vimeo_duration,
            'pivot'                  => $this->resource->pivot ? [
                'date'        => $this->resource->pivot->date,
                'room'        => $this->resource->pivot->room,
                'time_ends'   => $this->resource->pivot->time_ends,
                'time_starts' => $this->resource->pivot->time_starts,
            ] : [],
            'created_at'             => $this->resource->created_at?->toDateTimeString() ?? ($this->resource->updated_at?->toDateTimeString()),
            'category'               => $this->resource->category->first()?->name ?? null,
            'classroom_courses'      => $this->resource->classroom_courses ?? [],
            'video_courses'          => $this->resource->video_courses ?? [],
            'live_streaming_courses' => $this->resource->live_streaming_courses ?? [],
        ];
    }
}
