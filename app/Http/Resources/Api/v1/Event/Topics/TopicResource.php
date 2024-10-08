<?php

namespace App\Http\Resources\Api\v1\Event\Topics;

use App\Http\Resources\Api\v1\Event\Lesson\LessonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'pivot' => $this->resource->pivot ? [
                'automate_mail' => $this->resource->pivot->automate_mail ?? 0,
                'topic_id' => $this->resource->pivot?->topic_id,
                'event_id' => $this->resource->pivot?->event_id,
            ] : [],
            'event_lesson' => LessonResource::collection($this->whenLoaded('event_lesson')),
            'status' => $this->resource->status,
            'created_at' => $this->resource->created_at,
            'short_title' => $this->resource->short_title,
            'subtitle' => $this->resource->subtitle,
            'header' => $this->resource->header,
            'summary' => $this->resource->summary,
            'body' => $this->resource->body,
            'email_template' => $this->resource->email_template,
            'lessons_count' => $this->resource->lessons_count,
            'courses_count' => $this->resource->courses_count,
            'has_exams' => $this->resource->exam_count > 0,
            'classroom_courses' => $this->resource->classroom_courses ?? [],
            'video_courses' => $this->resource->video_courses ?? [],
            'live_streaming_courses' => $this->resource->live_streaming_courses ?? [],
            'exams' => $this->resource->exams ?? [],
            'messages' => $this->resource->messages ?? [],
            'messages_rules' => $this->resource->messages_rules ?? null,
        ];
    }

    public static function addEventsInfo($topics)
    {
        $uniqueEventTopics = [];
        foreach ($topics as $topic) {
            $loopEventId = [];
            foreach ($topic['events'] as $event) {
                if (!in_array($event['id'], $loopEventId)) {
                    $topicClone = $topic->toArray();
                    $topicClone['event_name'] = $event['title'];
                    $topicClone['event_id'] = $event['id'];
                    $topicClone['pivot_topic_id'] = $event['pivot']['id'];

                    $uniqueEventTopics[] = $topicClone;
                    $loopEventId[] = $event['id'];
                }
            }
        }

        return ['data'=>$uniqueEventTopics];
    }
}
