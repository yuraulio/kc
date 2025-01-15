<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Model\Lesson;
use App\Model\Topic;
use App\Model\TopicLesson;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TopicService
{
    public function filterQuery(array $data): Builder
    {
        return Topic::query()
            ->with('lessons.event')
            ->withCount(['lessons', 'exam'])
            ->when(array_key_exists('delivery', $data), function ($query) use ($data) {
                $query->whereHas('lessons', function ($query) use ($data) {
                    $query->whereHas('event', function ($query) use ($data) {
                        $query->whereHas('deliveries', function ($query) use ($data) {
                            $query->where('deliveries.id', $data['delivery']);
                        });
                    });
                });
            })->when(array_key_exists('course', $data), function ($query) use ($data) {
                $query->whereHas('lessons', function ($query) use ($data) {
                    $query->whereHas('event', function ($query) use ($data) {
                        $query->where('events.id', $data['course']);
                    });
                });
            })->when(array_key_exists('date_from', $data), function ($q) use ($data) {
                $q->whereDate('users.created_at', '>=', Carbon::parse($data['date_from']));
            })->when(array_key_exists('date_to', $data), function ($q) use ($data) {
                $q->whereDate('users.created_at', '<=', Carbon::parse($data['date_to']));
            })->when(array_key_exists('query', $data), function ($q) use ($data) {
                $q->where(function ($q) use ($data) {
                    $q->where('users.title', 'like', '%' . $data['query'] . '%');
                });
            })->orderBy($data['order_by'] ?? 'id', $data['order_type'] ?? 'desc');
    }

    public function attachLesson(Topic $topic, Lesson $lesson): bool
    {
        $existsAlready = $item = TopicLesson::query()
            ->where('topic_id', $topic->id)
            ->where('lesson_id', $lesson->id)
            ->exists();

        if ($existsAlready) {
            return true;
        }

        $priority = TopicLesson::query()
                ->where('topic_id', $topic->id)
                ->orderBy('priority', 'desc')
                ->first()
                ->priority ?? 0;

        $topic->lessonList()->syncWithoutDetaching([$lesson->id => ['priority' => $priority + 1]]);

        return true;
    }

    public function changePriority(Topic $topic, Lesson $lesson, int $newPriority): bool
    {
        $item = TopicLesson::query()
            ->where('topic_id', $topic->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        $oldPriority = $item->priority;

        if (!$item || $oldPriority === $newPriority) {
            return true;
        }

        if ($newPriority > $oldPriority) {
            TopicLesson::where('priority', '>', $oldPriority)
                ->where('priority', '<=', $newPriority)
                ->where('topic_id', $topic->id)
                ->update([
                    'priority' => DB::raw('priority - 1'),
                ]);
        } else {
            TopicLesson::where('priority', '<', $oldPriority)
                ->where('priority', '>=', $newPriority)
                ->where('topic_id', $topic->id)
                ->update([
                    'priority' => DB::raw('priority + 1'),
                ]);
        }

        $item->update(['priority' => $newPriority]);

        return true;
    }
}
